<?php

namespace App\Http\Controllers\Personnel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Personnel\{tperso_cooperative_minerais};
use App\Traits\{GlobalMethod,Slug};
use DB;

use App\User;
use App\Message;

class tperso_cooperative_mineraisController extends Controller
{
    use GlobalMethod;
    use Slug;
    public function index(Request $request)
    {
        //
        
        if (!is_null($request->get('query'))) {
            # code...
            $query = $this->Gquery($request);
            $data = DB::table('tperso_cooperative_minerais')
            ->select("tperso_cooperative_minerais.id","tperso_cooperative_minerais.nom_coop",
            'responsable_coop','contact_respo_coop','description_coop',
            "tperso_cooperative_minerais.created_at")
            ->where('nom_coop', 'like', '%'.$query.'%')
            ->orderBy("tperso_cooperative_minerais.id", "desc")
            ->paginate(10);

            return response($data, 200);

            // return response()->json([
            //     'data'  => $data,
            // ]);
           

        }
        else{
            $data = DB::table('tperso_cooperative_minerais')
            ->select("tperso_cooperative_minerais.id","tperso_cooperative_minerais.nom_coop",
            'responsable_coop','contact_respo_coop','description_coop',
            "tperso_cooperative_minerais.created_at")
            ->orderBy("tperso_cooperative_minerais.id", "desc")
            ->paginate(10);

            return response($data, 200);
            // return response()->json([
            //     'data'  => $data,
            // ]);
        }
    }


    function fetch_dropdown_2()
    {
        $data = DB::table('tperso_cooperative_minerais')
        ->select("tperso_cooperative_minerais.id","tperso_cooperative_minerais.nom_coop",
        'responsable_coop','contact_respo_coop','description_coop',
        "tperso_cooperative_minerais.created_at")
        ->orderBy("id", "desc")
        ->get();
        return response()->json([
            'data'  => $data
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
            $data = tperso_cooperative_minerais::where("id", $request->id)->update([
                'nom_coop' =>  $request->nom_coop,
                'responsable_coop' =>  $request->responsable_coop,
                'contact_respo_coop' =>  $request->contact_respo_coop,
                'description_coop' =>  $request->description_coop
            ]);
            return response()->json([
                'data'  =>  "Modification  avec succès!!!"
            ]);
        }
        else
        {
     
            $data = tperso_cooperative_minerais::create([
                'nom_coop' =>  $request->nom_coop,
                'responsable_coop' =>  $request->responsable_coop,
                'contact_respo_coop' =>  $request->contact_respo_coop,
                'description_coop' =>  $request->description_coop
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
        $data = tperso_cooperative_minerais::where('id', $id)->get();
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
        $data = tperso_cooperative_minerais::where('id', $id)->delete();
        return $this->msgJson('Suppression avec succès!!!');
    }


}
