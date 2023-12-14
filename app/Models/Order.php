<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'User_id',
    ];
    public function users(){
        return $this->belongsTo(User::class);
    }
    public function orders_medicines(){
        return $this->hasMany(Order_Medicines::class,'Orders_id');
    }
    public function medicines(){
        return $this->belongsToMany(Medicine::class,'orders_medicines');
    }

}
