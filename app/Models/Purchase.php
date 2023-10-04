<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'vender_id',
        'warehouse_id',
        'purchase_date',
        'purchase_number',
        'discount_apply',
        'category_id',
        'created_by',
    ];
    public static $statues = [
        'Pending',
        'Pending',
        'Unpaid',
        'Partialy Paid',
        'Paid',
    ];
    
    public static $goods_received = [
        'Not Received',
        'Partial',
        'Received',
        'Not Applicable',
    ];
    public function vender()
    {
        return $this->hasOne('App\Models\Vender', 'id', 'vender_id');
    }

    public function tax()
    {
        return $this->hasOne('App\Models\Tax', 'id', 'tax_id');
    }

    public function items()
    {
        return $this->hasMany('App\Models\PurchaseProduct', 'purchase_id', 'id');
    }
    public function payments()
    {
        return $this->hasMany('App\Models\PurchasePayment', 'purchase_id', 'id');
    }
    public function category()
    {
        return $this->hasOne('App\Models\ProductServiceCategory', 'id', 'category_id');
    }
    public function getSubTotal()
    {
        $subTotal = 0;
        foreach($this->items as $product)
        {
            $subTotal += ($product->price * $product->quantity);
        }

        return $subTotal;
    }
    public function getTotal()
    {
        return ($this->getSubTotal() + $this->getTotalTax()) - $this->getTotalDiscount();
    }
    public function getTotalTax()
    {
        $totalTax = 0;
        foreach($this->items as $product)
        {
            $taxes = Utility::totalTaxRate($product->tax);

            $totalTax += ($taxes / 100) * ($product->price * $product->quantity);

        }

        return $totalTax;
    }
    public function getTotalDiscount()
    {
        $totalDiscount = 0;
        foreach($this->items as $product)
        {
            $totalDiscount += $product->discount;
        }

        return $totalDiscount;
    }
    public function getDue()
    {
        $due = 0;
        foreach($this->payments as $payment)
        {
            $due += $payment->amount;
        }
		$total = round($this->getTotal(),2);
		return ($total - $due);
    }
    public function lastPayments()
    {
        return $this->hasOne('App\Models\PurchasePayment', 'id', 'purchase_id');
    }

    public function taxes()
    {
        return $this->hasOne('App\Models\Tax', 'id', 'tax');
    }


}