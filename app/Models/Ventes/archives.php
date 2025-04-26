<?php

namespace App\Models\Ventes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class archives extends Model
{
    protected $fillable=['id','nom_fichier','code','hash_pdf','adresse_blockchain'];
    protected $table = 'archives';
}
