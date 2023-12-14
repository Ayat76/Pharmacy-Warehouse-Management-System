<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_Medicines extends Model
{
    use HasFactory;
    public $table = 'orders_medicines';
    protected $fillable = [
        'Orders_id',
        'Medicines_id',
        'Required_quantity',
        'quantity_price',
        'Price_Medicine',
    ];
    public function orders(){
        return  $this->belongsTo(Order::class);
    }
    public function medicines(){
        return  $this->belongsTo(Medicine::class);
    }
}
