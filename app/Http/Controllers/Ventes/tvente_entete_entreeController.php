<?php

namespace App\Http\Controllers\Ventes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ventes\tvente_entete_entree;
use App\Models\Ventes\tvente_detail_entree;
use App\Traits\{GlobalMethod,Slug};
use DB;

class tvente_entete_entreeController extends Controller
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

        $data = DB::table('tvente_entete_entree')
        ->join('tvente_module','tvente_module.id','=','tvente_entete_entree.module_id')
        ->join('tvente_services','tvente_services.id','=','tvente_entete_entree.refService')
        
        ->join('tperso_affectation_agent','tperso_affectation_agent.id','=','tvente_entete_entree.refFournisseur')
        ->join('tagent','tagent.id','=','tperso_affectation_agent.refAgent')
        ->join('tperso_typecontrat','tperso_typecontrat.id','=','tperso_affectation_agent.refTypeContrat')

        ->select('tvente_entete_entree.id','tvente_entete_entree.code',
        'refFournisseur','refRecquisition','tvente_entete_entree.module_id',
        'tvente_entete_entree.refService',
        'dateEntree','tvente_entete_entree.libelle','transporteur','niveau1','niveaumax',
        'tvente_entete_entree.active','tvente_entete_entree.author','tvente_entete_entree.refUser',
        'tvente_entete_entree.created_at', "tvente_module.nom_module","nom_service",'montant'
    
        ,'refAgent','refServicePerso','refCategorieAgent','refPoste','refLieuAffectation',
        'refMutuelle','refTypeContrat','dateAffectation','dureecontrat','dureeLettre',
        'dateDebutEssaie','dateFinEssaie','JourTrail1','JourTrail2','heureTrail1','heureTrail2',
        'TempsPause','nbrConge','nbrCongeLettre','nomOffice','postnomOffice','qualifieOffice',
        'codeAgent','directeur','numCNSS','numImpot','numcpteBanque','BanqueAgant','autresDetail',
        'conge',"tperso_affectation_agent.author","matricule_agent","nummaison_agent",
        "noms_agent as noms","sexe_agent","datenaissance_agent","lieunaissnce_agent",
        "provinceOrigine_agent","etatcivil_agent","refAvenue_agent","contact_agent as contact",
        "mail_agent as mail","grade_agent","fonction_agent","specialite_agent","Categorie_agent",
        "niveauEtude_agent","anneeFinEtude_agent","Ecole_agent",
        "tagent.photo as photo_agent","tagent.slug as slug_agent",'fammiliale','logement',
        'tperso_affectation_agent.transport','sal_brut','sal_brut_imposable',
        'inss_qpo','inss_qpp','cnss','inpp','onem','ipr','mission','nom_contrat','code_contrat',
         'param_secteur_id')
        ->selectRaw('TIMESTAMPDIFF(YEAR, datenaissance_agent, CURDATE()) as age_agent')   
        ->selectRaw('TIMESTAMPDIFF(MONTH, CURDATE(), dateFin) as dureerestante')
        ->selectRaw('DATE_SUB(dateFin, INTERVAL 1 DAY) as dateFin');
        if (!is_null($request->get('query'))) {
            # code...
            $query = $this->Gquery($request);

            $data->where('noms_agent', 'like', '%'.$query.'%')          
            ->orderBy("tvente_entete_entree.created_at", "desc");

            return $this->apiData($data->paginate(10));
           

        }
        $data->orderBy("tvente_entete_entree.created_at", "desc");
        return $this->apiData($data->paginate(10));
        
    }


    public function fetch_data_entete(Request $request,$refEntete)
    { 
        $data = DB::table('tvente_entete_entree')
        ->join('tvente_module','tvente_module.id','=','tvente_entete_entree.module_id')
        ->join('tvente_services','tvente_services.id','=','tvente_entete_entree.refService')
        
        ->join('tperso_affectation_agent','tperso_affectation_agent.id','=','tvente_entete_entree.refFournisseur')
        ->join('tagent','tagent.id','=','tperso_affectation_agent.refAgent')
        ->join('tperso_typecontrat','tperso_typecontrat.id','=','tperso_affectation_agent.refTypeContrat')

        ->select('tvente_entete_entree.id','tvente_entete_entree.code',
        'refFournisseur','refRecquisition','tvente_entete_entree.module_id',
        'tvente_entete_entree.refService',
        'dateEntree','tvente_entete_entree.libelle','transporteur','niveau1','niveaumax',
        'tvente_entete_entree.active','tvente_entete_entree.author','tvente_entete_entree.refUser',
        'tvente_entete_entree.created_at', "tvente_module.nom_module","nom_service",'montant'
    
        ,'refAgent','refServicePerso','refCategorieAgent','refPoste','refLieuAffectation',
        'refMutuelle','refTypeContrat','dateAffectation','dureecontrat','dureeLettre',
        'dateDebutEssaie','dateFinEssaie','JourTrail1','JourTrail2','heureTrail1','heureTrail2',
        'TempsPause','nbrConge','nbrCongeLettre','nomOffice','postnomOffice','qualifieOffice',
        'codeAgent','directeur','numCNSS','numImpot','numcpteBanque','BanqueAgant','autresDetail',
        'conge',"tperso_affectation_agent.author","matricule_agent","nummaison_agent",
        "noms_agent as noms","sexe_agent","datenaissance_agent","lieunaissnce_agent",
        "provinceOrigine_agent","etatcivil_agent","refAvenue_agent","contact_agent as contact",
        "mail_agent as mail","grade_agent","fonction_agent","specialite_agent","Categorie_agent",
        "niveauEtude_agent","anneeFinEtude_agent","Ecole_agent",
        "tagent.photo as photo_agent","tagent.slug as slug_agent",'fammiliale','logement',
        'tperso_affectation_agent.transport','sal_brut','sal_brut_imposable',
        'inss_qpo','inss_qpp','cnss','inpp','onem','ipr','mission','nom_contrat','code_contrat',
         'param_secteur_id')
        ->selectRaw('TIMESTAMPDIFF(YEAR, datenaissance_agent, CURDATE()) as age_agent')   
        ->selectRaw('TIMESTAMPDIFF(MONTH, CURDATE(), dateFin) as dureerestante')
        ->selectRaw('DATE_SUB(dateFin, INTERVAL 1 DAY) as dateFin')
        ->Where('refFournisseur',$refEntete);
        if (!is_null($request->get('query'))) {
            # code...
            $query = $this->Gquery($request);

            $data ->where('noms_agent', 'like', '%'.$query.'%')          
            ->orderBy("tvente_entete_entree.created_at", "desc");
            return $this->apiData($data->paginate(10));         

        }       
        $data->orderBy("tvente_entete_entree.created_at", "desc");
        return $this->apiData($data->paginate(10));
    }    

    function fetch_list_fournisseur()
    {
        $data = DB::table('tperso_affectation_agent')
        ->select("tperso_affectation_agent.id","tperso_affectation_agent.noms_agent as noms")
        ->get();
        return response()->json([
            'data'  => $data,
        ]);
    }
    

    function fetch_single_data($id)
    {
        $data = DB::table('tvente_entete_entree')
        ->join('tvente_module','tvente_module.id','=','tvente_entete_entree.module_id')
        ->join('tvente_services','tvente_services.id','=','tvente_entete_entree.refService')
        
        ->join('tperso_affectation_agent','tperso_affectation_agent.id','=','tvente_entete_entree.refFournisseur')
        ->join('tagent','tagent.id','=','tperso_affectation_agent.refAgent')
        ->join('tperso_typecontrat','tperso_typecontrat.id','=','tperso_affectation_agent.refTypeContrat')

        ->select('tvente_entete_entree.id','tvente_entete_entree.code',
        'refFournisseur','refRecquisition','tvente_entete_entree.module_id',
        'tvente_entete_entree.refService',
        'dateEntree','tvente_entete_entree.libelle','transporteur','niveau1','niveaumax',
        'tvente_entete_entree.active','tvente_entete_entree.author','tvente_entete_entree.refUser',
        'tvente_entete_entree.created_at', "tvente_module.nom_module","nom_service",'montant'
    
        ,'refAgent','refServicePerso','refCategorieAgent','refPoste','refLieuAffectation',
        'refMutuelle','refTypeContrat','dateAffectation','dureecontrat','dureeLettre',
        'dateDebutEssaie','dateFinEssaie','JourTrail1','JourTrail2','heureTrail1','heureTrail2',
        'TempsPause','nbrConge','nbrCongeLettre','nomOffice','postnomOffice','qualifieOffice',
        'codeAgent','directeur','numCNSS','numImpot','numcpteBanque','BanqueAgant','autresDetail',
        'conge',"tperso_affectation_agent.author","matricule_agent","nummaison_agent",
        "noms_agent as noms","sexe_agent","datenaissance_agent","lieunaissnce_agent",
        "provinceOrigine_agent","etatcivil_agent","refAvenue_agent","contact_agent as contact",
        "mail_agent as mail","grade_agent","fonction_agent","specialite_agent","Categorie_agent",
        "niveauEtude_agent","anneeFinEtude_agent","Ecole_agent",
        "tagent.photo as photo_agent","tagent.slug as slug_agent",'fammiliale','logement',
        'tperso_affectation_agent.transport','sal_brut','sal_brut_imposable',
        'inss_qpo','inss_qpp','cnss','inpp','onem','ipr','mission','nom_contrat','code_contrat',
         'param_secteur_id')
        ->selectRaw('TIMESTAMPDIFF(YEAR, datenaissance_agent, CURDATE()) as age_agent')   
        ->selectRaw('TIMESTAMPDIFF(MONTH, CURDATE(), dateFin) as dureerestante')
        ->selectRaw('DATE_SUB(dateFin, INTERVAL 1 DAY) as dateFin')
        ->where('tvente_entete_entree.id', $id)
        ->get();

        return response()->json([
            'data'  => $data,
        ]);
    }
    function insert_data(Request $request)
    {  
        $code = $this->GetCodeData('tvente_param_systeme','module_id',$request->module_id);     
        $data = tvente_entete_entree::create([
            'code'       =>  $code,
            'refFournisseur'       =>  $request->refFournisseur,
            'refRecquisition'       =>  $request->refRecquisition,
            'module_id'       =>  $request->module_id,
            'refService'       =>  $request->refService,
            'dateEntree'    =>  $request->dateEntree,
            'libelle'    =>  $request->libelle,
            'transporteur'    =>  $request->transporteur,
            'niveau1'    =>  0,
            'niveaumax'    =>  3,
            'active'    =>  $request->active,            
            'author'       =>  $request->author,
            'refUser'    =>  $request->refUser,
        ]);
        return response()->json([
            'data'  =>  "Insertion avec succès!!!",
        ]);
    }

    function update_data(Request $request, $id)
    {
        $data = tvente_entete_entree::where('id', $id)->update([
            'refFournisseur'       =>  $request->refFournisseur,
            'refRecquisition'       =>  $request->refRecquisition,
            'module_id'       =>  $request->module_id,
            'refService'       =>  $request->refService,
            'dateEntree'    =>  $request->dateEntree,
            'libelle'    =>  $request->libelle,
            'transporteur'    =>  $request->transporteur,
            'niveau1'    =>  1,
            'niveaumax'    =>  3,
            'active'    =>  $request->active,            
            'author'       =>  $request->author,
            'refUser'    =>  $request->refUser,
        ]);
        return response()->json([
            'data'  =>  "Modification  avec succès!!!",
        ]);
    }

    function delete_data($id)
    {


        $qte=0;
        $idProduit=0;        
        $pu=0;
        $montantreduction=0;
        $montanttva=0;
        $idStockService=0;
        $idDetail=0;
        $qteBase = 0;

        $deleteds = DB::table('tvente_detail_entree')->Where('refEnteteEntree',$id)->get(); 
        foreach ($deleteds as $deleted) {
            $idDetail = $deleted->id;
            $qte = $deleted->qteEntree;            
            $pu = $deleted->puEntree;
            $idProduit = $deleted->refProduit;
            $idStockService = $deleted->idStockService;
            $qteBase = $deleted->qteBase;

            $qteEntree = floatval($qte) * floatval($qteBase);

   
            $data2 = DB::update(
                'update tvente_stock_service set qte = qte - :qteEntree where refProduit = :id',
                ['qteEntree' => $qteEntree,'id' => $idStockService]
            ); 

            $nom_table = 'tvente_detail_entree';

            $data4 = DB::update(
                'delete from tvente_mouvement_stock where tvente_mouvement_stock.id_data = :id and nom_table=:nom_table',
                ['id' => $idDetail, 'nom_table' => $nom_table]
            );
    
            $data1 = tvente_detail_entree::where('id',$idDetail)->delete();

        }

        $data1 = tvente_detail_entree::where('refEnteteEntree',$id)->delete();
        $data = tvente_entete_entree::where('id',$id)->delete();
        return response()->json([
            'data'  =>  "suppression avec succès",
        ]);
        
    }
}
