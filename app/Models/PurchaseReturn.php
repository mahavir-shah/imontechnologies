<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_product_id',
        'purchase_id',
        'return_id',
        'return_date',
        'required_qty',
        'returned_qty',
        'return_qty',
        'status',
        'created_by'
    ];
}
