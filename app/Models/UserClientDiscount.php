<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserClientDiscount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'discount',
        'client_type',
        'payment',
        'transaction_limit',
        'tread_discount',
        'status'
    ];
}
