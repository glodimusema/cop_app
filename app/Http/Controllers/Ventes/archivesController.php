<?php

namespace App\Http\Controllers\Ventes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categorie;
use App\Models\Ventes\{archives};
use App\Traits\{GlobalMethod,Slug};
use DB;

use App\User;
use App\Message;

// archives
// nom_fichier
// code_unite
// active

class archivesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    use GlobalMethod;
    use Slug;
    public function index(Request $request)
    {
        //id,nom_fichier,author

        $data = DB::table("archives")
        ->select("archives.id",'nom_fichier','code','hash_pdf','adresse_blockchain', "archives.created_at");

        if (!is_null($request->get('query'))) {
            # code...
            $query = $this->Gquery($request);

            $data->where('archives.nom_fichier', 'like', '%'.$query.'%')
            ->orderBy("archives.id", "desc");

            return $this->apiData($data->paginate(10));
           

        }
        return $this->apiData($data->paginate(10));
    }


    function fetch_archives_2()
    {
         $data = DB::table("archives")
         ->select("archives.id",'nom_fichier','code','hash_pdf',
         'adresse_blockchain', "archives.created_at")
        ->get();
        return response()->json(['data' => $data]);
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        if ($request->id !='') 
        {
            # code...
            // update  
            //,'nom_fichier','code','hash_pdf','adresse_blockchain'
            $data = archives::where("id", $request->id)->update([
                'nom_fichier' =>  $request->nom_fichier,
                'code' =>  $request->code,
                'hash_pdf' =>  $request->hash_pdf,
                'adresse_blockchain' =>  $request->adresse_blockchain
            ]);
            return $this->msgJson('Modification avec succès!!!');

        }
        else
        {
            // \App\Models\Archive::create($request->all());
            // return response()->json(['message' => 'Archive enregistrée'], 200);
        
            // insertion 
            $data = archives::create([
                'nom_fichier' =>  $request->nom_fichier,
                'code' =>  $request->code,
                'hash_pdf' =>  $request->hash_pdf,
                'adresse_blockchain' =>  $request->adresse_blockchain
            ]);

            return response()->json(['message' => 'Archive enregistrée'], 200);

            // return $this->msgJson('Insertion avec succès!!!');
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
        $data = archives::where('id', $id)->get();
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
        $data = archives::where('id', $id)->delete();
        return $this->msgJson('Suppression avec succès!!!');
    }

    public function destroyMessage($id)
    {
        //
        $data = Message::where('id', $id)->delete();
        return $this->msgJson('Suppression avec succès!!!');
    }
}
