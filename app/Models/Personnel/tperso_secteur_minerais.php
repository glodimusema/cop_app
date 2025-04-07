<?php

namespace App\Models\Personnel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tperso_secteur_minerais extends Model
{
    protected $fillable=['id','nom_secteur','description_secteur'];
    protected $table = 'tperso_secteur_minerais';
}
