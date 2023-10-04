<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\Bill;
use App\Models\CustomField;
use App\Models\ProductService;
use App\Models\ProductServiceCategory;
use App\Models\Purchase;
use App\Models\PurchaseProduct;
use App\Models\PurchasePayment;
use App\Models\PurchaseReturn;
use App\Models\PurchaseGoodsReceived;
use App\Models\PurchaseBarcode;
use App\Models\StockReport;
use App\Models\Transaction;
use App\Models\Vender;
use App\Models\User;
use App\Models\Utility;
use Illuminate\Support\Facades\Crypt;
use App\Models\warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RepairController extends Controller
{
    public function repairIn(Request $request)
    {
        return redirect()->back()->with('error', __('Not available.'));
    }

    public function repairOut(Request $request)
    {
        return redirect()->back()->with('error', __('Not available.'));
    }
}
