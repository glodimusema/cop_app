<?php

namespace App\Http\Controllers\Personnel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Personnel\{tperso_paramettre_secteur};
use App\Traits\{GlobalMethod,Slug};
use DB;

use App\User;
use App\Message;

class tperso_paramettre_secteurController extends Controller
{
    use GlobalMethod;
    use Slug;
    public function index(Request $request)
    {
        //
        
        if (!is_null($request->get('query'))) {
            # code...
            $query = $this->Gquery($request);
            $data = DB::table('tperso_paramettre_secteur')
            ->join('tperso_secteur_minerais','tperso_secteur_minerais.id','=','tperso_paramettre_secteur.refSecteur')
            ->join('tperso_cooperative_minerais','tperso_cooperative_minerais.id','=','tperso_paramettre_secteur.refCooperative')
            ->select("tperso_paramettre_secteur.id",'refCooperative','refSecteur','active_param',
            "tperso_paramettre_secteur.created_at","tperso_cooperative_minerais.nom_coop",
            'responsable_coop','contact_respo_coop','description_coop',
            "tperso_secteur_minerais.nom_secteur",'description_secteur')
            ->where('tperso_secteur_minerais.nom_secteur', 'like', '%'.$query.'%')
            ->orderBy("tperso_paramettre_secteur.id", "desc")
            ->paginate(10);

            return response($data, 200);

            // return response()->json([
            //     'data'  => $data,
            // ]);
           

        }
        else{
            $data = DB::table('tperso_paramettre_secteur')
            ->join('tperso_secteur_minerais','tperso_secteur_minerais.id','=','tperso_paramettre_secteur.refSecteur')
            ->join('tperso_cooperative_minerais','tperso_cooperative_minerais.id','=','tperso_paramettre_secteur.refCooperative')
            ->select("tperso_paramettre_secteur.id",'refCooperative','refSecteur','active_param',
            "tperso_paramettre_secteur.created_at","tperso_cooperative_minerais.nom_coop",
            'responsable_coop','contact_respo_coop','description_coop',
            "tperso_secteur_minerais.nom_secteur",'description_secteur')
            ->orderBy("tperso_paramettre_secteur.id", "desc")
            ->paginate(10);

            return response($data, 200);
            // return response()->json([
            //     'data'  => $data,
            // ]);
        }
    }


    function fetch_dropdown_2()
    {
        $data = DB::table('tperso_paramettre_secteur')
            ->join('tperso_secteur_minerais', 'tperso_secteur_minerais.id', '=', 'tperso_paramettre_secteur.refSecteur')
            ->join('tperso_cooperative_minerais', 'tperso_cooperative_minerais.id', '=', 'tperso_paramettre_secteur.refCooperative')
            ->select(
                "tperso_paramettre_secteur.id",
                'refCooperative',
                'refSecteur',
                'active_param',
                "tperso_paramettre_secteur.created_at",
                "tperso_cooperative_minerais.nom_coop",
                'responsable_coop',
                'contact_respo_coop',
                'description_coop',
                "tperso_secteur_minerais.nom_secteur",
                'description_secteur'
            )
            ->selectRaw('CONCAT(nom_coop, " - ", nom_secteur) as date_param_secteur') 
            ->orderBy("tperso_paramettre_secteur.id", "desc")
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        if ($request->id !='') 
        {
            //'id','refCooperative','refSecteur','active_param'
            $data = tperso_paramettre_secteur::where("id", $request->id)->update([
                'refCooperative' =>  $request->refCooperative,
                'refSecteur' =>  $request->refSecteur,
                'active_param' =>  $request->active_param
            ]);
            return response()->json([
                'data'  =>  "Modification  avec succès!!!"
            ]);
        }
        else
        {
     
            $data = tperso_paramettre_secteur::create([
                'refCooperative' =>  $request->refCooperative,
                'refSecteur' =>  $request->refSecteur,
                'active_param' =>  $request->active_param
            ]);

            return response()->json([
                'data'  =>  "Insertion avec succès!!!",
            ]);
        }
    }

    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $data = DB::table('tperso_paramettre_secteur')
        ->join('tperso_secteur_minerais','tperso_secteur_minerais.id','=','tperso_paramettre_secteur.refSecteur')
        ->join('tperso_cooperative_minerais','tperso_cooperative_minerais.id','=','tperso_paramettre_secteur.refCooperative')
        ->select("tperso_paramettre_secteur.id",'refCooperative','refSecteur','active_param',
        "tperso_paramettre_secteur.created_at","tperso_cooperative_minerais.nom_coop",
        'responsable_coop','contact_respo_coop','description_coop',
        "tperso_secteur_minerais.nom_secteur",'description_secteur')
        ->where('tperso_paramettre_secteur.id', $id)
        ->get();
        return response()->json(['data' => $data]);
    }

   

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $data = tperso_paramettre_secteur::where('id', $id)->delete();
        return $this->msgJson('Suppression avec succès!!!');
    }


}
