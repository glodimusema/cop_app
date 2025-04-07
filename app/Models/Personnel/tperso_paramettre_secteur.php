<?php

namespace App\Models\Personnel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tperso_paramettre_secteur extends Model
{
    protected $fillable=['id','refCooperative','refSecteur','active_param'];
    protected $table = 'tperso_paramettre_secteur';
}
