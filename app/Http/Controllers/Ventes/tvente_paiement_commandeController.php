<?php

namespace App\Http\Controllers\Ventes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ventes\tvente_paiement_commande;
use App\Models\Ventes\tvente_entete_paiecommande;
use App\Traits\{GlobalMethod,Slug};
use DB;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;

class tvente_paiement_commandeController extends Controller
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

    // 'id','tvente_entete_paiecommande.code','refEntetepaie','refCommande','refBanque','montant_paie','devise',
    // 'taux','date_paie','modepaie','libellepaie','numeroBordereau','author','refUser','active'
     

    public function all(Request $request)
    { 

        $data = DB::table('tvente_paiement_commande')
        ->join('tvente_entete_requisition','tvente_entete_requisition.id','=','tvente_paiement_commande.refCommande')
        ->join('tvente_entete_paiecommande','tvente_entete_paiecommande.id','=','tvente_paiement_commande.refEntetepaie')
        ->join('tvente_module','tvente_module.id','=','tvente_entete_requisition.module_id')
        ->join('tvente_services','tvente_services.id','=','tvente_entete_requisition.refService')
        
        ->join('tperso_affectation_agent','tperso_affectation_agent.id','=','tvente_entete_entree.refFournisseur')
        ->join('tagent','tagent.id','=','tperso_affectation_agent.refAgent')
        ->join('tperso_typecontrat','tperso_typecontrat.id','=','tperso_affectation_agent.refTypeContrat')

        ->join('tconf_banque' , 'tconf_banque.id','=','tvente_paiement_commande.refBanque')
        ->join('tfin_ssouscompte as compteBanque','compteBanque.id','=','tconf_banque.refSscompte')

        ->select('tvente_paiement_commande.id','tvente_entete_paiecommande.code','refEntetepaie','refCommande','refBanque',
        'montant_paie','tvente_paiement_commande.devise','tvente_paiement_commande.taux','date_paie',
        'modepaie','libellepaie','numeroBordereau','tvente_paiement_commande.author',
        'tvente_paiement_commande.refUser','tvente_paiement_commande.active','tvente_paiement_commande.created_at',

        "tvente_module.nom_module","tvente_services.nom_service",

        'refFournisseur','tvente_entete_paiecommande.module_id as module_idPaie',
        'tvente_entete_paiecommande.refService as refServicePaie','dateCmd','libelle','niveau1','niveaumax',
        'montant','paie',

        'date_entete_paie',
        
        "tconf_banque.nom_banque","tconf_banque.numerocompte",'tconf_banque.nom_mode',
        "tconf_banque.refSscompte as refSscompteBanque"
        ,'compteBanque.refSousCompte as refSousCompteBanque',
        'compteBanque.nom_ssouscompte as nom_ssouscompteBanque',
        'compteBanque.numero_ssouscompte as numero_ssouscompteBanque'
        
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
         'param_secteur_id'
        
        )
        ->selectRaw('((montant_paie)/tvente_paiement_commande.taux) as montant_paieFC')
        ->selectRaw('CONCAT("R",YEAR(date_paie),"",MONTH(date_paie),"00",tvente_paiement_commande.id) as codeRecu');
        if (!is_null($request->get('query'))) {
            # code...
            $query = $this->Gquery($request);

            $data->where('noms_agent', 'like', '%'.$query.'%')          
            ->orderBy("tvente_paiement_commande.created_at", "asc");

            return $this->apiData($data->paginate(10));
           

        }
        $data->orderBy("tvente_paiement_commande.created_at", "desc");
        return $this->apiData($data->paginate(10));
        
    }

    //insert_data_one
    public function fetch_data_entete(Request $request,$refEntete)
    { 

        $data = DB::table('tvente_paiement_commande')
        ->join('tvente_entete_requisition','tvente_entete_requisition.id','=','tvente_paiement_commande.refCommande')
        ->join('tvente_entete_paiecommande','tvente_entete_paiecommande.id','=','tvente_paiement_commande.refEntetepaie')
        ->join('tvente_module','tvente_module.id','=','tvente_entete_requisition.module_id')
        ->join('tvente_services','tvente_services.id','=','tvente_entete_requisition.refService')
        
        ->join('tperso_affectation_agent','tperso_affectation_agent.id','=','tvente_entete_entree.refFournisseur')
        ->join('tagent','tagent.id','=','tperso_affectation_agent.refAgent')
        ->join('tperso_typecontrat','tperso_typecontrat.id','=','tperso_affectation_agent.refTypeContrat')

        ->join('tconf_banque' , 'tconf_banque.id','=','tvente_paiement_commande.refBanque')
        ->join('tfin_ssouscompte as compteBanque','compteBanque.id','=','tconf_banque.refSscompte')

        ->select('tvente_paiement_commande.id','tvente_entete_paiecommande.code','refEntetepaie','refCommande','refBanque',
        'montant_paie','tvente_paiement_commande.devise','tvente_paiement_commande.taux','date_paie',
        'modepaie','libellepaie','numeroBordereau','tvente_paiement_commande.author',
        'tvente_paiement_commande.refUser','tvente_paiement_commande.active','tvente_paiement_commande.created_at',

        "tvente_module.nom_module","tvente_services.nom_service",

        'refFournisseur','tvente_entete_paiecommande.module_id as module_idPaie',
        'tvente_entete_paiecommande.refService as refServicePaie','dateCmd','libelle','niveau1','niveaumax',
        'montant','paie',

        'date_entete_paie',
        
        "tconf_banque.nom_banque","tconf_banque.numerocompte",'tconf_banque.nom_mode',
        "tconf_banque.refSscompte as refSscompteBanque"
        ,'compteBanque.refSousCompte as refSousCompteBanque',
        'compteBanque.nom_ssouscompte as nom_ssouscompteBanque',
        'compteBanque.numero_ssouscompte as numero_ssouscompteBanque'
        
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
         'param_secteur_id'        
        )
        ->selectRaw('((montant_paie)/tvente_paiement_commande.taux) as montant_paieFC')
        ->selectRaw('CONCAT("R",YEAR(date_paie),"",MONTH(date_paie),"00",tvente_paiement_commande.id) as codeRecu')
        ->Where('refEntetepaie',$refEntete);
        if (!is_null($request->get('query'))) {
            # code...
            $query = $this->Gquery($request);

            $data ->where('noms_agent', 'like', '%'.$query.'%')          
            ->orderBy("tvente_paiement_commande.created_at", "desc");
            return $this->apiData($data->paginate(10));         

        }       
        $data->orderBy("tvente_paiement_commande.created_at", "desc");
        return $this->apiData($data->paginate(10));
    }
    
    public function fetch_data_entete_cmd(Request $request,$refEntete)
    { 

        $data = DB::table('tvente_paiement_commande')
        ->join('tvente_entete_requisition','tvente_entete_requisition.id','=','tvente_paiement_commande.refCommande')
        ->join('tvente_entete_paiecommande','tvente_entete_paiecommande.id','=','tvente_paiement_commande.refEntetepaie')
        ->join('tvente_module','tvente_module.id','=','tvente_entete_requisition.module_id')
        ->join('tvente_services','tvente_services.id','=','tvente_entete_requisition.refService')
        
        ->join('tperso_affectation_agent','tperso_affectation_agent.id','=','tvente_entete_entree.refFournisseur')
        ->join('tagent','tagent.id','=','tperso_affectation_agent.refAgent')
        ->join('tperso_typecontrat','tperso_typecontrat.id','=','tperso_affectation_agent.refTypeContrat')

        ->join('tconf_banque' , 'tconf_banque.id','=','tvente_paiement_commande.refBanque')
        ->join('tfin_ssouscompte as compteBanque','compteBanque.id','=','tconf_banque.refSscompte')

        ->select('tvente_paiement_commande.id','tvente_entete_paiecommande.code','refEntetepaie','refCommande','refBanque',
        'montant_paie','tvente_paiement_commande.devise','tvente_paiement_commande.taux','date_paie',
        'modepaie','libellepaie','numeroBordereau','tvente_paiement_commande.author',
        'tvente_paiement_commande.refUser','tvente_paiement_commande.active','tvente_paiement_commande.created_at',

        "tvente_module.nom_module","tvente_services.nom_service",

        'refFournisseur','tvente_entete_paiecommande.module_id as module_idPaie',
        'tvente_entete_paiecommande.refService as refServicePaie','dateCmd','libelle','niveau1','niveaumax',
        'montant','paie',

        'date_entete_paie',
        
        "tconf_banque.nom_banque","tconf_banque.numerocompte",'tconf_banque.nom_mode',
        "tconf_banque.refSscompte as refSscompteBanque"
        ,'compteBanque.refSousCompte as refSousCompteBanque',
        'compteBanque.nom_ssouscompte as nom_ssouscompteBanque',
        'compteBanque.numero_ssouscompte as numero_ssouscompteBanque'
        
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
         'param_secteur_id'        
        )
        ->selectRaw('((montant_paie)/tvente_paiement_commande.taux) as montant_paieFC')
        ->selectRaw('CONCAT("R",YEAR(date_paie),"",MONTH(date_paie),"00",tvente_paiement_commande.id) as codeRecu')
        ->Where('refCommande',$refEntete);
        if (!is_null($request->get('query'))) {
            # code...
            $query = $this->Gquery($request);

            $data ->where('noms_agent', 'like', '%'.$query.'%')          
            ->orderBy("tvente_paiement_commande.created_at", "desc");
            return $this->apiData($data->paginate(10));         

        }       
        $data->orderBy("tvente_paiement_commande.created_at", "desc");
        return $this->apiData($data->paginate(10));
    }

    function fetch_single_data($id)
    {
        $data= DB::table('tvente_paiement_commande')
        ->join('tvente_entete_requisition','tvente_entete_requisition.id','=','tvente_paiement_commande.refCommande')
        ->join('tvente_entete_paiecommande','tvente_entete_paiecommande.id','=','tvente_paiement_commande.refEntetepaie')
        ->join('tvente_module','tvente_module.id','=','tvente_entete_requisition.module_id')
        ->join('tvente_services','tvente_services.id','=','tvente_entete_requisition.refService')
        
        ->join('tperso_affectation_agent','tperso_affectation_agent.id','=','tvente_entete_entree.refFournisseur')
        ->join('tagent','tagent.id','=','tperso_affectation_agent.refAgent')
        ->join('tperso_typecontrat','tperso_typecontrat.id','=','tperso_affectation_agent.refTypeContrat')

        ->join('tconf_banque' , 'tconf_banque.id','=','tvente_paiement_commande.refBanque')
        ->join('tfin_ssouscompte as compteBanque','compteBanque.id','=','tconf_banque.refSscompte')

        ->select('tvente_paiement_commande.id','tvente_entete_paiecommande.code','refEntetepaie','refCommande','refBanque',
        'montant_paie','tvente_paiement_commande.devise','tvente_paiement_commande.taux','date_paie',
        'modepaie','libellepaie','numeroBordereau','tvente_paiement_commande.author',
        'tvente_paiement_commande.refUser','tvente_paiement_commande.active','tvente_paiement_commande.created_at',

        "tvente_module.nom_module","tvente_services.nom_service",

        'refFournisseur','tvente_entete_paiecommande.module_id as module_idPaie',
        'tvente_entete_paiecommande.refService as refServicePaie','dateCmd','libelle','niveau1','niveaumax',
        'montant','paie',

        'date_entete_paie',
        
        "tconf_banque.nom_banque","tconf_banque.numerocompte",'tconf_banque.nom_mode',
        "tconf_banque.refSscompte as refSscompteBanque"
        ,'compteBanque.refSousCompte as refSousCompteBanque',
        'compteBanque.nom_ssouscompte as nom_ssouscompteBanque',
        'compteBanque.numero_ssouscompte as numero_ssouscompteBanque'
        
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
         'param_secteur_id'        
        )
        ->selectRaw('((montant_paie)/tvente_paiement_commande.taux) as montant_paieFC')
        ->selectRaw('CONCAT("R",YEAR(date_paie),"",MONTH(date_paie),"00",tvente_paiement_commande.id) as codeRecu')
        ->where('tvente_paiement_commande.id', $id)
        ->get();

        return response()->json([
            'data'  => $data,
        ]);
    }
    
    function insert_data(Request $request)
    {
        $datetest='';
        $data3 = DB::table('tfin_cloture_caisse')
       ->select('date_cloture')
       ->where('date_cloture','=', $request->date_paie)
       ->take(1)
       ->orderBy('id', 'desc')         
       ->get();    
       foreach ($data3 as $row) 
       {                           
          $datetest=$row->date_cloture;          
       }

       if($datetest == $request->date_paie)
       {
            return response()->json([
                'data'  =>  "La Caisse est déja cloturée pour cette date svp!!! Veuillez prendre la date du jour suivant!!!",
            ]);            
       }
       else
       {
        $taux=0;
        $data5 =  DB::table("tvente_taux")
        ->select("tvente_taux.id", "tvente_taux.taux", 
        "tvente_taux.created_at", "tvente_taux.author")
         ->get(); 
         $output='';
         foreach ($data5 as $row) 
         {                                
            $taux=$row->taux;                           
         }

        $montants=0;
        $devises='';
        if($request->devise != 'USD')
        {
            $montants = ($request->montant_paie)/$taux;
            $devises='USD';
        }
        else
        {
            $montants = $request->montant_paie;
            $devises = $request->devise;
        }
        // 

        $idFacture=$request->refCommande;

        $code = $this->GetCode2('tvente_paiement_commande');
       
        $data = tvente_paiement_commande::create([
            'code'       =>  $code,
            'refEntetepaie'       =>  $request->refEntetepaie,
            'refCommande'       =>  $request->refCommande,
            'montant_paie'    =>  $montants,
            'devise'    =>  $devises,
            'taux'    =>  $taux,
            'date_paie'    =>  $request->date_paie,
            'modepaie'       =>  $request->modepaie,
            'libellepaie'       =>  $request->libellepaie, 
            'refBanque'       =>  $request->refBanque,
            'numeroBordereau'       =>  $request->numeroBordereau,
            'author'       =>  $request->author,
            'refUser'       =>  $request->refUser,
            'active'       =>  $request->active
        ]);

        $data3 = DB::update(
            'update tvente_entete_requisition set paie = paie + (:paiement) where id = :refCommande',
            ['paiement' => $montants,'refCommande' => $idFacture]
        );

        return response()->json([
            'data'  =>  "Insertion avec succès!!!",
        ]);

       }



    }

    function insert_data_one(Request $request)
    {
        $current = Carbon::now(); 
        $refEntetepaie=0; 
        $active = "OUI";
        $refService = 0;
        $module_id = 6;      

        $data_vente = DB::table('tvente_entete_requisition')       
        ->select('id','code','refFournisseur','module_id','refService','dateCmd','libelle',
                    'niveau1','niveaumax','active','montant','paie','author','refUser')
        ->where('tvente_entete_requisition.id', $request->refCommande)
        ->get();
        foreach ($data_vente as $list) {
            $refService= $list->refService;
        }
        $code = $this->GetCodeData('tvente_param_systeme','module_id',$module_id); 
        

        $data = tvente_entete_paiecommande::create([
            'code'       =>  $code,
            'date_entete_paie'    =>  $current,
            'refService'    =>  $refService,
            'module_id'    =>  $module_id,
            'author'       =>  $request->author,
            'refUser'       =>  $request->refUser
        ]);
        
        $idmax=0;
        $maxid = DB::table('tvente_entete_paiecommande')       
        ->selectRaw('MAX(tvente_entete_paiecommande.id) as code_entete')
        ->where('tvente_entete_paiecommande.refService', $refService)
        ->get();
        foreach ($maxid as $list) {
            $idmax= $list->code_entete;
        }

        $datetest='';
        $data3 = DB::table('tfin_cloture_caisse')
       ->select('date_cloture')
       ->where('date_cloture','=', $request->date_paie)
       ->take(1)
       ->orderBy('id', 'desc')         
       ->get();    
       foreach ($data3 as $row) 
       {                           
          $datetest=$row->date_cloture;          
       }

       if($datetest == $request->date_paie)
       {
            return response()->json([
                'data'  =>  "La Caisse est déja cloturée pour cette date svp!!! Veuillez prendre la date du jour suivant!!!",
            ]);            
       }
       else
       {
        $taux=0;
        $data5 =  DB::table("tvente_taux")
        ->select("tvente_taux.id", "tvente_taux.taux", 
        "tvente_taux.created_at", "tvente_taux.author")
         ->get(); 
         $output='';
         foreach ($data5 as $row) 
         {                                
            $taux=$row->taux;                           
         }

        $montants=0;
        $devises='';
        if($request->devise != 'USD')
        {
            $montants = ($request->montant_paie)/$taux;
            $devises='USD';
        }
        else
        {
            $montants = $request->montant_paie;
            $devises = $request->devise;
        }
        $idFacture=$request->refCommande;

        $data = tvente_paiement_commande::create([
            'code'       =>  $code,
            'refEntetepaie'       =>  $idmax,
            'refCommande'       =>  $request->refCommande,
            'montant_paie'    =>  $montants,
            'devise'    =>  $devises,
            'taux'    =>  $taux,
            'date_paie'    =>  $request->date_paie,
            'modepaie'       =>  $request->modepaie,
            'libellepaie'       =>  $request->libellepaie, 
            'refBanque'       =>  $request->refBanque,
            'numeroBordereau'       =>  $request->numeroBordereau,
            'author'       =>  $request->author,
            'refUser'       =>  $request->refUser,
            'active'       =>  $active
        ]);

        $data3 = DB::update(
            'update tvente_entete_requisition set paie = paie + (:paiement) where id = :refCommande',
            ['paiement' => $montants,'refCommande' => $idFacture]
        );

        return response()->json([
            'data'  =>  "Insertion avec succès!!!",
        ]);

       }



    }

    function update_data(Request $request, $id)
    {
        $datetest='';
        $data3 = DB::table('tfin_cloture_caisse')
       ->select('date_cloture')
       ->where('date_cloture','=', $request->date_paie)
       ->take(1)
       ->orderBy('id', 'desc')         
       ->get();    
       foreach ($data3 as $row) 
       {                           
          $datetest=$row->date_cloture;          
       }

       if($datetest == $request->date_paie)
       {
            return response()->json([
                'data'  =>  "La Caisse est déja cloturée pour cette date svp!!! Veuillez prendre la date du jour suivant!!!",
            ]);            
       }
       else
       {
        $taux=0;
        $data5 =  DB::table("tvente_taux")
        ->select("tvente_taux.id", "tvente_taux.taux", 
        "tvente_taux.created_at", "tvente_taux.author")
         ->get(); 
         $output='';
         foreach ($data5 as $row) 
         {                                
            $taux=$row->taux;                           
         }

        $montants=0;
        $devises='';
        if($request->devise != 'USD')
        {
            $montants = ($request->montant_paie)/$taux;
            $devises='USD';
        }
        else
        {
            $montants = $request->montant_paie;
            $devises = $request->devise;
        }


        $data = tvente_paiement_commande::where('id', $id)->update([            
            'refEntetepaie'       =>  $request->refEntetepaie,
            'refCommande'       =>  $request->refCommande,
            'montant_paie'    =>  $montants,
            'devise'    =>  $devises,
            'taux'    =>  $taux,
            'date_paie'    =>  $request->date_paie,
            'modepaie'       =>  $request->modepaie,
            'libellepaie'       =>  $request->libellepaie, 
            'refBanque'       =>  $request->refBanque,
            'numeroBordereau'       =>  $request->numeroBordereau,
            'author'       =>  $request->author,
            'refUser'       =>  $request->refUser,
            'active'       =>  $request->active
        ]);
        return response()->json([
            'data'  =>  "Modification  avec succès!!!",
        ]);

       }
    }

    function delete_data($id)
    {
        $idFacture=0;
        $montants=0;

        $deleteds = DB::table('tvente_paiement_commande')->Where('id',$id)->get(); 
        foreach ($deleteds as $deleted) {
            $idFacture = $deleted->refCommande;
            $montants = $deleted->montant_paie;        
        }
        
        $data3 = DB::update(
            'update tvente_entete_requisition set paie = paie - (:paiement) where id = :refCommande',
            ['paiement' => $montants,'refCommande' => $idFacture]
        );

        $data = tvente_paiement_commande::where('id',$id)->delete();
              
        return response()->json([
            'data'  =>  "suppression avec succès",
        ]);
        
    }
}
