<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosPackingDetail extends Model
{
    use HasFactory;

    protected $fillable = ['pos_id','ship_id','picking_id','product_id','carton','qty','created_by'];
}
