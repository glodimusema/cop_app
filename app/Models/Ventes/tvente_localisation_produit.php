<?php

namespace App\Models\Ventes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tvente_localisation_produit extends Model
{
    protected $fillable=['id','refProduit','latitude','longitude'];
    protected $table = 'tvente_localisation_produit';
}



