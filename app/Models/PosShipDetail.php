<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosShipDetail extends Model
{
    use HasFactory;

    protected $fillable = [ 'pos_id','pos_ship_id','product_total','carton','delivery','carrier_type','carrier_name','tracking_number','width','weight','height','length','box_type','status','created_by'];
}
