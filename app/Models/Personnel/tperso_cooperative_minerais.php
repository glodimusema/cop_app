<?php

namespace App\Models\Personnel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tperso_cooperative_minerais extends Model
{
    protected $fillable=['id','nom_coop','responsable_coop','contact_respo_coop','description_coop'];
    protected $table = 'tperso_cooperative_minerais';
}
