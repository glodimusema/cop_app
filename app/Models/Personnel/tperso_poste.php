<?php

namespace App\Models\Personnel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tperso_poste extends Model
{
    protected $fillable=['id','nom_poste','description_poste','transport'];
    protected $table = 'tperso_poste';

    // tperso_poste  id,nom_poste,description_poste
    // tperso_lieuaffectation  id,nom_lieu,description_lieu
    // tperso_mutuelle  id,nom_mutuelle,description_mutuelle
    // tperso_typecontrat  id,nom_contrat,code_contrat
}
