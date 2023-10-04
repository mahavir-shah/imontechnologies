<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosShip extends Model
{
    use HasFactory;

    protected $fillable = ['pos_id','ship_unique','customer_id','total_amt','carton','status','created_by'];

    public function customer()
    {
        return $this->hasOne('App\Models\Customer', 'id', 'customer_id');
    }
    public function shipDetail()
    {
        return $this->hasOne('App\Models\PosShipDetail', 'pos_ship_id', 'id');
    }
    public function items()
    {
        return $this->hasMany('App\Models\PosProduct', 'pos_id', 'id');
    }
    public function posPayment(){
        return $this->hasOne('App\Models\PosPayment','pos_id','id');
    }
}
