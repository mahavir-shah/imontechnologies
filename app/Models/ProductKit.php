<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductKit extends Model
{
    use HasFactory;

    protected $fillable = [
        'kit_name',
        'kit_price',
        'product_info',
        'total_alliance_purchase_price',
        'total_premium_purchase_price',
        'total_saller_purchase_price',
        'total_cost_price',
        'created_by',
        'product_id',
        'status',
    ];
}
