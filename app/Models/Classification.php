<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classification extends Model
{
    use HasFactory;
    public function medicines(){
        return  $this->hasMany(Medicine::class,'classification_id');
    }
}
