<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseBarcode extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'product_id',
        'purchase_product_id',
        'sr_no',
        'barcode',
        'status',
        'created_by'
    ];
}
