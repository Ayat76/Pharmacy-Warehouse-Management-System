<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;
    protected $fillable = [
        'Classification_id',
        'Scientific_name',
        'Commercial_name',
        'Manufacturer',
        'Available_Quantity',
        'Expiry_date',
        'Price',
    ];
    public function orders(){
        return $this->belongsToMany(Order::class,'orders_medicines');
    }
    public function users(){
        return $this->belongsToMany(User::class,'favorites_list');
    }
    public function classifications(){
        return $this->belongsTo(Classification::class);
    }
}
