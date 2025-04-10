<?php

namespace App\Http\Controllers\Personnel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Personnel\{tperso_annee};
use App\Traits\{GlobalMethod,Slug};
use DB;

use App\User;
use App\Message;

class tperso_anneeController extends Controller
{
    use GlobalMethod;
    use Slug;
    public function index(Request $request)
    {
        //
        
        if (!is_null($request->get('query'))) {
            # code...
            $query = $this->Gquery($request);
            $data = DB::table('tperso_annee')->select("tperso_annee.id","tperso_annee.name_annee","tperso_annee.created_at")->where('name_annee', 'like', '%'.$query.'%')
            ->orWhere('name_annee', 'like', '%'.$query.'%')
            ->orderBy("tperso_annee.id", "desc")
            ->paginate(10);

           return response($data, 200);
           

        }
        else{
            $data = DB::table('tperso_annee')
            ->select("tperso_annee.id","tperso_annee.name_annee","tperso_annee.created_at")
            ->orderBy("tperso_annee.id", "desc")->paginate(10);
            
           return response($data, 200);
        }
    }


    function fetch_dropdown_2()
    {
        $data = DB::table('tperso_annee')
        ->select("tperso_annee.id","tperso_annee.name_annee","tperso_annee.created_at")
        ->orderBy("id", "desc")->get();
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
        
        if ($request->id !='') 
        {
 
            $data = tperso_annee::where("id", $request->id)->update([
                'name_annee' =>  $request->name_annee
            ]);
            return response()->json([
                'data'  =>  "Modification  avec succès!!!"
            ]);
        }
        else
        {
     
            $data = tperso_annee::create([

                'name_annee' =>$request->name_annee
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
        $data = tperso_annee::where('id', $id)->get();
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
        $data = tperso_annee::where('id', $id)->delete();
        return $this->msgJson('Suppression avec succès!!!');
    }


}
