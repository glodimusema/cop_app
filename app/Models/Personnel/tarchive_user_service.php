<?php

namespace App\Models\Personnel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tarchive_user_service extends Model
{
    protected $fillable=['id','refUser','refService','active','author'];
    protected $table = 'tarchive_user_service'; 
}



