<?php

namespace App\Http\Controllers\Personnel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Personnel\{tarchive_user_service};
use App\Models\Produit;
use App\Traits\{GlobalMethod,Slug};
use DB;

use App\User;
use App\Message;


class tarchive_user_serviceController extends Controller
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
        $data = DB::table('tarchive_user_service')
        ->join('users','users.id','=','tarchive_user_service.refUser')
        ->join('roles','users.id_role','=','roles.id')
        ->join('tperso_service_archivage','tperso_service_archivage.id','=','tarchive_user_service.refService')
        ->join('tperso_categorie_archivage','tperso_categorie_archivage.id','=','tperso_service_archivage.categorie_id')
        ->join('tperso_division','tperso_division.id','=','tperso_service_archivage.division_id')
        ->select("tarchive_user_service.id",'tarchive_user_service.refUser','tarchive_user_service.refService',
        'tarchive_user_service.active','tarchive_user_service.author',"tarchive_user_service.created_at",
        "name_service","description_service","categorie_id","division_id","tperso_categorie_archivage.name_categorie",
        "description_categorie","name_division","description_division",
        'users.sexe','users.telephone','users.adresse','users.name');
        if (!is_null($request->get('query'))) {
            # code...
            $query = $this->Gquery($request);

            $data->where('users.name', 'like', '%'.$query.'%')
            ->orderBy("tarchive_user_service.id", "desc");

            return $this->apiData($data->paginate(10));
           

        }
        return $this->apiData($data->paginate(10));
    }


    public function entete_data(Request $request,$refUser)
    {
        $data = DB::table('tarchive_user_service')
        ->join('users','users.id','=','tarchive_user_service.refUser')
        ->join('roles','users.id_role','=','roles.id')
        ->join('tperso_service_archivage','tperso_service_archivage.id','=','tarchive_user_service.refService')
        ->join('tperso_categorie_archivage','tperso_categorie_archivage.id','=','tperso_service_archivage.categorie_id')
        ->join('tperso_division','tperso_division.id','=','tperso_service_archivage.division_id')
        ->select("tarchive_user_service.id",'tarchive_user_service.refUser','tarchive_user_service.refService',
        'tarchive_user_service.active','tarchive_user_service.author',"tarchive_user_service.created_at",
        "name_service","description_service","categorie_id","division_id","tperso_categorie_archivage.name_categorie",
        "description_categorie","name_division","description_division",
        'users.sexe','users.telephone','users.adresse','users.name') 
        ->where([
            ['tarchive_user_service.refUser', $refUser],
            ['tarchive_user_service.active','=', 'OUI']
         ]);
        if (!is_null($request->get('query'))) {
            # code...
            $query = $this->Gquery($request);

            $data->where('users.name', 'like', '%'.$query.'%')
            ->orderBy("tarchive_user_service.id", "desc");

            return $this->apiData($data->paginate(10));
           

        }
        return $this->apiData($data->paginate(10));
    }



    function fetch_tarchive_user_service_2()
    {
         $data = DB::table('tarchive_user_service')
         ->join('users','users.id','=','tarchive_user_service.refUser')
         ->join('roles','users.id_role','=','roles.id')
         ->join('tperso_service_archivage','tperso_service_archivage.id','=','tarchive_user_service.refService')
         ->join('tperso_categorie_archivage','tperso_categorie_archivage.id','=','tperso_service_archivage.categorie_id')
         ->join('tperso_division','tperso_division.id','=','tperso_service_archivage.division_id')
         ->select("tarchive_user_service.id",'tarchive_user_service.refUser','tarchive_user_service.refService',
         'tarchive_user_service.active','tarchive_user_service.author',"tarchive_user_service.created_at",
         "name_service","description_service","categorie_id","division_id","tperso_categorie_archivage.name_categorie",
         "description_categorie","name_division","description_division",
         'users.sexe','users.telephone','users.adresse','users.name')        
        ->get();
        
        return response()->json(['data' => $data]);

    }


    function fetch_service_user_by_user($refUser)
    {
        $data = DB::table('tarchive_user_service')
        ->join('users','users.id','=','tarchive_user_service.refUser')
        ->join('roles','users.id_role','=','roles.id')
        ->join('tperso_service_archivage','tperso_service_archivage.id','=','tarchive_user_service.refService')
        ->join('tperso_categorie_archivage','tperso_categorie_archivage.id','=','tperso_service_archivage.categorie_id')
        ->join('tperso_division','tperso_division.id','=','tperso_service_archivage.division_id')
        ->select("tarchive_user_service.id",'tarchive_user_service.refUser','tarchive_user_service.refService',
        'tarchive_user_service.active','tarchive_user_service.author',"tarchive_user_service.created_at",
        "name_service","description_service","categorie_id","division_id","tperso_categorie_archivage.name_categorie",
        "description_categorie","name_division","description_division",
        'users.sexe','users.telephone','users.adresse','users.name')
        ->where([
            ['tarchive_user_service.refUser', $refUser],
            ['tarchive_user_service.active','=', 'OUI']
         ])
        ->get();

        return response()->json([
            'data'  => $data,
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
       //
        if ($request->id !='') 
        {
            # code...
            // update  'id','refUser','refService','active','author'
            $data = tarchive_user_service::where("id", $request->id)->update([
                'refUser'       =>  $request->refUser,
                'refService'    =>  $request->refService,
                'active'    =>  $request->active,
                'author'    =>  $request->author
            ]);
            return $this->msgJson('Modification avec succès!!!');

        }
        else
        {
            // insertion 
            $data = tarchive_user_service::create([
                'refUser'       =>  $request->refUser,
                'refService'    =>  $request->refService,
                'active'    =>  $request->active,
                'author'    =>  $request->author
            ]);

            return $this->msgJson('Insertion avec succès!!!');
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
        $data = tarchive_user_service::where('id', $id)->get();
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
        $data = tarchive_user_service::where('id', $id)->delete();
        return $this->msgJson('Suppression avec succès!!!');
    }

    public function destroyMessage($id)
    {
        //
        $data = Message::where('id', $id)->delete();
        return $this->msgJson('Suppression avec succès!!!');
    }
}
