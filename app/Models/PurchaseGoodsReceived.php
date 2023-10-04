<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseGoodsReceived extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_product_id',
        'goods_unique_id',
        'purchase_id',
        'received_date',
        'required_qty',
        'receiving_qty',
        'status',
        'created_by'
    ];

    public static $goods_received = [
        'Not Received',
        'Received',
        'Partial',
        'Not Applicable',
    ];
}
