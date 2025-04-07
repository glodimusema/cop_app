<?php

namespace App\Http\Controllers\Personnel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Personnel\{tperso_secteur_minerais};
use App\Traits\{GlobalMethod,Slug};
use DB;

use App\User;
use App\Message;

class tperso_secteur_mineraisController extends Controller
{
    use GlobalMethod;
    use Slug;
    public function index(Request $request)
    {        
        if (!is_null($request->get('query'))) {
            # code...
            $query = $this->Gquery($request);
            $data = DB::table('tperso_secteur_minerais')
            ->select("tperso_secteur_minerais.id","tperso_secteur_minerais.nom_secteur",
            'description_secteur',"tperso_secteur_minerais.created_at")
            ->where('nom_secteur', 'like', '%'.$query.'%')
            ->orderBy("tperso_secteur_minerais.id", "desc")
            ->paginate(10);

            return response($data, 200);           

        }
        else{
            $data = DB::table('tperso_secteur_minerais')
            ->select("tperso_secteur_minerais.id","tperso_secteur_minerais.nom_secteur",
            'description_secteur',"tperso_secteur_minerais.created_at")
            ->orderBy("tperso_secteur_minerais.id", "desc")
            ->paginate(10);

            return response($data, 200);
            // return response()->json([
            //     'data'  => $data,
            // ]);
        }
    }


    function fetch_dropdown_2()
    {
        $data = DB::table('tperso_secteur_minerais')
        ->select("tperso_secteur_minerais.id","tperso_secteur_minerais.nom_secteur",
        'description_secteur',"tperso_secteur_minerais.created_at")
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
        //'id','nom_secteur','description_secteur'
        
        if ($request->id !='') 
        {
            $data = tperso_secteur_minerais::where("id", $request->id)->update([
                'nom_secteur' =>  $request->nom_secteur,
                'description_secteur' =>  $request->description_secteur
            ]);
            return response()->json([
                'data'  =>  "Modification  avec succès!!!"
            ]);
        }
        else
        {
     
            $data = tperso_secteur_minerais::create([
                'nom_secteur' =>  $request->nom_secteur,
                'description_secteur' =>  $request->description_secteur
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
        $data = tperso_secteur_minerais::where('id', $id)->get();
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
        $data = tperso_secteur_minerais::where('id', $id)->delete();
        return $this->msgJson('Suppression avec succès!!!');
    }


}
