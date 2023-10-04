<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'return_id',
        'pos_id',
        'pos_product_id',
        'required_qty',
        'returned_qty',
        'return_qty',
        'return_date',
        'status',
        'created_by'
    ];
}
