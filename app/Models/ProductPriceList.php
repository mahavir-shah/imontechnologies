<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPriceList extends Model
{
    use HasFactory;

    protected $fillable = [ 'product_id','name','sku','description','purchase_price','import_cost_per','msp_margin_per','lsp_margin_per','alliance_per','premium_per','standard_per',
    'import_cost','msp_margin','lsp_margin','alliance','premium','standard' ];
}
