<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosPickingDetail extends Model
{
    use HasFactory;

    protected $fillable = ['pos_id','ship_id','product_id','qty','created_by'];
}
