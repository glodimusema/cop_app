<?php

namespace App\Http\Controllers\Ventes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ventes\tvente_localisation_produit;
use App\Traits\{GlobalMethod,Slug};
use DB;

class tvente_localisation_produitController extends Controller
{

    use GlobalMethod, Slug;

    public function index()
    {
        return 'hello';
    }

    function Gquery($request)
    {
      return str_replace(" ", "%", $request->get('query'));
      // return $request->get('query');
    }


    public function all(Request $request)
    { 

        $data = DB::table('tvente_localisation_produit')
        ->join('tvente_produit','tvente_produit.id','=','tvente_localisation_produit.refProduit')
        ->join('tvente_categorie_produit','tvente_categorie_produit.id','=','tvente_produit.refCategorie')  
        ->select('tvente_localisation_produit.id','refProduit','latitude','longitude',
        "tvente_produit.designation as designation",'refCategorie','refUniteBase','uniteBase','pu','qte',
        'cmup','devise','taux','Oldcode','Newcode','tvaapplique','estvendable',
        "tvente_categorie_produit.designation as Categorie");
        if (!is_null($request->get('query'))) {
            # code...
            $query = $this->Gquery($request);

            $data->where('tvente_produit.designation', 'like', '%'.$query.'%')          
            ->orderBy("tvente_localisation_produit.created_at", "desc");

            return $this->apiData($data->paginate(10));
           

        }
        $data->orderBy("tvente_localisation_produit.created_at", "desc");
        return $this->apiData($data->paginate(10));
        
    }


    public function fetch_data_entete(Request $request,$refEntete)
    { 
        $data = DB::table('tvente_localisation_produit')
        ->join('tvente_produit','tvente_produit.id','=','tvente_localisation_produit.refProduit')
        ->join('tvente_categorie_produit','tvente_categorie_produit.id','=','tvente_produit.refCategorie')  
        ->select('tvente_localisation_produit.id','refProduit','latitude','longitude',
        "tvente_produit.designation as designation",'refCategorie','refUniteBase','uniteBase','pu','qte',
        'cmup','devise','taux','Oldcode','Newcode','tvaapplique','estvendable',
        "tvente_categorie_produit.designation as Categorie")
        ->Where('refProduit',$refEntete);
        if (!is_null($request->get('query'))) {
            # code...
            $query = $this->Gquery($request);

            $data ->where('tvente_produit.designation', 'like', '%'.$query.'%')          
            ->orderBy("tvente_localisation_produit.created_at", "desc");
            return $this->apiData($data->paginate(10));         

        }       
        $data->orderBy("tvente_localisation_produit.created_at", "desc");
        return $this->apiData($data->paginate(10));
    }    

     

    function fetch_single_data($id)
    {
        $data = DB::table('tvente_localisation_produit')
        ->join('tvente_produit','tvente_produit.id','=','tvente_localisation_produit.refProduit')
        ->join('tvente_categorie_produit','tvente_categorie_produit.id','=','tvente_produit.refCategorie')  
        ->select('tvente_localisation_produit.id','refProduit','latitude','longitude',
        "tvente_produit.designation as designation",'refCategorie','refUniteBase','uniteBase','pu','qte',
        'cmup','devise','taux','Oldcode','Newcode','tvaapplique','estvendable',
        "tvente_categorie_produit.designation as Categorie")
        ->where('tvente_localisation_produit.id', $id)
        ->get();

        return response()->json([
            'data'  => $data,
        ]);
    }

    function fetch_data_map()
    {
        $data = DB::table('tvente_localisation_produit')
        ->join('tvente_produit','tvente_produit.id','=','tvente_localisation_produit.refProduit')
        ->join('tvente_categorie_produit','tvente_categorie_produit.id','=','tvente_produit.refCategorie')  
        ->select('tvente_localisation_produit.id','refProduit','latitude','longitude',
        "tvente_produit.designation as designation",'refCategorie','refUniteBase','uniteBase','pu','qte',
        'cmup','devise','taux','Oldcode','Newcode','tvaapplique','estvendable',
        "tvente_categorie_produit.designation as Categorie")
        ->get();

        return response()->json($data);
    }

   //'id','refProduit','latitude','longitude'
    function insert_data(Request $request)
    {       
        $data = tvente_localisation_produit::create([
            'refProduit'       =>  $request->refProduit,
            'latitude'    =>  $request->latitude,
            'longitude'    =>  $request->longitude
        ]);
        return response()->json([
            'data'  =>  "Insertion avec succès!!!",
        ]);
    }

    function update_data(Request $request, $id)
    {
        $data = tvente_localisation_produit::where('id', $id)->update([
            'refProduit'       =>  $request->refProduit,
            'latitude'    =>  $request->latitude,
            'longitude'    =>  $request->longitude
        ]);
        return response()->json([
            'data'  =>  "Modification  avec succès!!!",
        ]);
    }

    function delete_data($id)
    {
        $data = tvente_localisation_produit::where('id',$id)->delete();
        return response()->json([
            'data'  =>  "suppression avec succès",
        ]);
        
    }
}
