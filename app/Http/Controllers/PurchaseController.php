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
use App\Models\ProductPriceList;
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

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $vender = Vender::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $vender->prepend('Select Vendor', '');
        $status = Purchase::$statues;
        $purchases = Purchase::where('created_by', '=', \Auth::user()->creatorId())->orderby('id','DESC')->get();

        $getGoodsRecord = $this->goodsFilter();
        $goodRecordsId = [];
        $goodCompleted = [];
		
		foreach ($purchases as $key => $value) {
			$total_required_qty = PurchaseProduct::selectRaw('SUM(`quantity`) `required_qty`')->where(["purchase_id"=>$value['id']])->groupBy('purchase_id')->get()->first();
				
			$total_received_qty = PurchaseGoodsReceived::selectRaw('SUM(`receiving_qty`) `total_received`')->where(["purchase_id"=>$value['id']])->groupBy('purchase_id')->get()->first();
			
			if($total_required_qty && $total_received_qty && $total_required_qty->required_qty == $total_received_qty->total_received){
				array_push($goodCompleted,$value['id']);
			}
		}
		
        foreach ($getGoodsRecord as $key => $value) {
            /* if($value['required_qty'] == $value['totalQty']){
                array_push($goodCompleted,$value['purchase_id']);
            } */
            array_push($goodRecordsId,$value['purchase_id']);
        }
		
        $goodCompleted = array_unique($goodCompleted);
        $goodRecordsId = array_unique($goodRecordsId);

        $getReturnRecord = $this->purchaseReturnFilter();
        $returnRecordsId = [];
        $returnCompleted = [];
        foreach ($getReturnRecord as $key => $value) {
            if($value['required_qty'] == $value['totalReturnQty']){
                array_push($returnCompleted,$value['purchase_id']);
            }
            array_push($returnRecordsId,$value['purchase_id']);
        }
        $returnCompleted = array_unique($returnCompleted);
        $returnRecordsId = array_unique($returnRecordsId);

        return view('purchase.index', compact('purchases', 'status','vender','goodCompleted','goodRecordsId','returnRecordsId','returnCompleted'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($vendorId)
    {
        if(\Auth::user()->can('create purchase'))
        {
            $customFields = CustomField::where('created_by', '=', \Auth::user()->creatorId())->where('module', '=', 'purchase')->get();
            $category     = ProductServiceCategory::where('created_by', \Auth::user()->creatorId())->where('type', 2)->get()->pluck('name', 'id');
            $category->prepend('Select Category', '');

            $purchase_number = \Auth::user()->purchaseNumberFormat($this->purchaseNumber());
            $venders     = Vender::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $venders->prepend('Select Vender', '');

            $warehouse     = warehouse::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $warehouse->prepend('Select Warehouse', '');

            $product_services = ProductService::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $product_services->prepend('--', '');

            return view('purchase.create', compact('venders', 'purchase_number', 'product_services', 'category', 'customFields','vendorId','warehouse'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        if(\Auth::user()->can('create purchase'))
        {
            $validator = \Validator::make(
                $request->all(), [
                    'vender_id' => 'required',
                    'warehouse_id' => 'required',
                    'purchase_date' => 'required',
                    'items' => 'required',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $purchase                 = new Purchase();
            $purchase->purchase_id    = $this->purchaseNumber();
            $purchase->vender_id      = $request->vender_id;
            $purchase->warehouse_id      = $request->warehouse_id;
            $purchase->purchase_date  = $request->purchase_date;
            $purchase->due_date  = $request->due_date;
            $purchase->purchase_number   = !empty($request->purchase_number) ? $request->purchase_number : 0;
            $purchase->status         =  0;
//            $purchase->discount_apply = isset($request->discount_apply) ? 1 : 0;
            $purchase->created_by     = \Auth::user()->creatorId();
            $purchase->save();

            $products = $request->items;

            for($i = 0; $i < count($products); $i++)
            {
                $purchaseProduct              = new PurchaseProduct();
                $purchaseProduct->purchase_id     = $purchase->id;
                $purchaseProduct->product_id  = $products[$i]['item'];
                $purchaseProduct->quantity    = $products[$i]['quantity'];
                $purchaseProduct->tax         = $products[$i]['tax'];
//                $purchaseProduct->discount    = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
                $purchaseProduct->discount    = $products[$i]['discount'];
                $purchaseProduct->price       = $products[$i]['price'];
                $purchaseProduct->description = $products[$i]['description'];
                $purchaseProduct->save();

                //Warehouse Stock Report
                Utility::addWarehouseStock( $products[$i]['item'],$products[$i]['quantity'],$request->warehouse_id);

            }

            return redirect()->route('purchase.index', $purchase->id)->with('success', __('Purchase successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function show($ids)
    {

        if(\Auth::user()->can('show purchase'))
        {
            $id   = Crypt::decrypt($ids);
            $purchase = Purchase::find($id);

            if($purchase->created_by == \Auth::user()->creatorId())
            {

                $purchasePayment = PurchasePayment::where('purchase_id', $purchase->id)->first();
                $vendor      = $purchase->vender;
                $iteams      = $purchase->items;



                return view('purchase.view', compact('purchase', 'vendor', 'iteams', 'purchasePayment'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function edit($idsd)
    {
        if(\Auth::user()->can('edit purchase'))
        {

            $idwww   = Crypt::decrypt($idsd);
            $purchase     = Purchase::find($idwww);
            $category = ProductServiceCategory::where('created_by', \Auth::user()->creatorId())->where('type', 2)->get()->pluck('name', 'id');
            $category->prepend('Select Category', '');
            $warehouse     = warehouse::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');

            $purchase_number      = \Auth::user()->purchaseNumberFormat($purchase->id);
            $venders          = Vender::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $product_services = ProductService::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');



            return view('purchase.edit', compact('venders', 'product_services', 'purchase', 'warehouse','purchase_number', 'category'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, Purchase $purchase)
    {
        if(\Auth::user()->can('edit purchase'))
        {

            if($purchase->created_by == \Auth::user()->creatorId())
            {
                $validator = \Validator::make(
                    $request->all(), [
                        'vender_id' => 'required',
                        'purchase_date' => 'required',
                        'items' => 'required',
                    ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->route('purchase.index')->with('error', $messages->first());
                }
                $purchase->vender_id      = $request->vender_id;
                $purchase->purchase_date      = $request->purchase_date;
                $purchase->due_date      = $request->due_date;
//                $purchase->discount_apply = isset($request->discount_apply) ? 1 : 0;
                $purchase->save();
                $products = $request->items;

                for($i = 0; $i < count($products); $i++)
                {
                    $purchaseProduct = PurchaseProduct::find($products[$i]['id']);

                    if($purchaseProduct == null)
                    {
                        $purchaseProduct             = new PurchaseProduct();
                        $purchaseProduct->purchase_id    = $purchase->id;
//                        $purchaseProduct->product_id = $products[$i]['item'];
                    }

                    if(isset($products[$i]['item']))
                    {
                        $purchaseProduct->product_id = $products[$i]['item'];
                    }

                    $purchaseProduct->quantity    = $products[$i]['quantity'];
                    $purchaseProduct->tax         = $products[$i]['tax'];
//                    $purchaseProduct->discount    = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
                    $purchaseProduct->discount    = $products[$i]['discount'];
                    $purchaseProduct->price       = $products[$i]['price'];
                    $purchaseProduct->description = $products[$i]['description'];
                    $purchaseProduct->save();

                    if(isset($products[$i]['item'])){

                        Utility::addWarehouseStock( $products[$i]['item'],$products[$i]['quantity'],$request->warehouse_id);
                    }
                    //Warehouse Stock Report

                }

                return redirect()->route('purchase.index')->with('success', __('Purchase successfully updated.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchase $purchase)
    {
        if(\Auth::user()->can('delete purchase'))
        {
            if($purchase->created_by == \Auth::user()->creatorId())
            {
//                $purchasepayments = $purchase->payments;
//                foreach($purchasepayments as $key => $value)
//                {
//                    $transaction= Transaction::where('payment_id',$value->id)->first();
//                    $transaction->delete();
//
//                    $purchasepayment = PurchasePayment::find($value->id)->first();
//                    $purchasepayment->delete();
//
//                }
                $purchase->delete();
//                if($purchase->vender_id != 0)
//                {
//                    Utility::userBalance('vendor', $purchase->vender_id, $purchase->getTotal(), 'debit');
//                }
                PurchaseProduct::where('purchase_id', '=', $purchase->id)->delete();

                return redirect()->route('purchase.index')->with('success', __('Purchase successfully deleted.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    function purchaseNumber()
    {
        $latest = Purchase::where('created_by', '=', \Auth::user()->creatorId())->latest()->first();
        if(!$latest)
        {
            return 1;
        }

        return $latest->id + 1;
    }
    public function sent($id)
    {
        if(\Auth::user()->can('send purchase'))
        {
            $purchase            = Purchase::where('id', $id)->first();
            $purchase->send_date = date('Y-m-d');
            $purchase->status    = 1;
            $purchase->save();

            $vender = Vender::where('id', $purchase->vender_id)->first();

            $purchase->name = !empty($vender) ? $vender->name : '';
            $purchase->purchase = \Auth::user()->purchaseNumberFormat($purchase->id);

            $purchaseId    = Crypt::encrypt($purchase->id);
            $purchase->url = route('purchase.pdf', $purchaseId);

            Utility::userBalance('vendor', $vender->id, $purchase->getTotal(), 'credit');

            $vendorArr = [
                'vender_bill_name' => $purchase->name,
                'vender_bill_number' =>$purchase->purchase,
                'vender_bill_url' => $purchase->url,

            ];
            $resp = \App\Models\Utility::sendEmailTemplate('vender_bill_sent', [$vender->id => $vender->email], $vendorArr);


            return redirect()->back()->with('success', __('Purchase successfully sent.') . (($resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    public function resent($id)
    {

        if(\Auth::user()->can('send purchase'))
        {
            $purchase = Purchase::where('id', $id)->first();

            $vender = Vender::where('id', $purchase->vender_id)->first();

            $purchase->name = !empty($vender) ? $vender->name : '';
            $purchase->purchase = \Auth::user()->purchaseNumberFormat($purchase->id);

            $purchaseId    = Crypt::encrypt($purchase->id);
            $purchase->url = route('purchase.pdf', $purchaseId);
//

        // Send Email
//        $setings = Utility::settings();
//
//        if($setings['bill_resend'] == 1)
//        {
//            $bill = Bill::where('id', $id)->first();
//            $vender = Vender::where('id', $bill->vender_id)->first();
//            $bill->name = !empty($vender) ? $vender->name : '';
//            $bill->bill = \Auth::user()->billNumberFormat($bill->bill_id);
//            $billId    = Crypt::encrypt($bill->id);
//            $bill->url = route('bill.pdf', $billId);
//            $billResendArr = [
//                'vender_name'   => $vender->name,
//                'vender_email'  => $vender->email,
//                'bill_name'  => $bill->name,
//                'bill_number'   => $bill->bill,
//                'bill_url' =>$bill->url,
//            ];
//
//            $resp = Utility::sendEmailTemplate('bill_resend', [$vender->id => $vender->email], $billResendArr);
//
//
//        }
//
//        return redirect()->back()->with('success', __('Bill successfully sent.') . (($resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
//
        return redirect()->back()->with('success', __('Bill successfully sent.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    public function purchase($purchase_id)
    {


        $settings = Utility::settings();
        $purchaseId   = Crypt::decrypt($purchase_id);

        $purchase  = Purchase::where('id', $purchaseId)->first();
        $data  = DB::table('settings');
        $data  = $data->where('created_by', '=', $purchase->created_by);
        $data1 = $data->get();

        foreach($data1 as $row)
        {
            $settings[$row->name] = $row->value;
        }

        $vendor = $purchase->vender;

        $totalTaxPrice = 0;
        $totalQuantity = 0;
        $totalRate     = 0;
        $totalDiscount = 0;
        $taxesData     = [];
        $items         = [];

        foreach($purchase->items as $product)
        {

            $item              = new \stdClass();
            $item->name        = !empty($product->product()) ? $product->product()->name : '';
            $item->quantity    = $product->quantity;
            $item->tax         = $product->tax;
            $item->discount    = $product->discount;
            $item->price       = $product->price;
            $item->description = $product->description;

            $totalQuantity += $item->quantity;
            $totalRate     += $item->price;
            $totalDiscount += $item->discount;

            $taxes     = Utility::tax($product->tax);
            $itemTaxes = [];
            if(!empty($item->tax))
            {
                foreach($taxes as $tax)
                {
                    $taxPrice      = Utility::taxRate($tax->rate, $item->price, $item->quantity);
                    $totalTaxPrice += $taxPrice;

                    $itemTax['name']  = $tax->name;
                    $itemTax['rate']  = $tax->rate . '%';
                    $itemTax['price'] = Utility::priceFormat($settings, $taxPrice);
                    $itemTaxes[]      = $itemTax;


                    if(array_key_exists($tax->name, $taxesData))
                    {
                        $taxesData[$tax->name] = $taxesData[$tax->name] + $taxPrice;
                    }
                    else
                    {
                        $taxesData[$tax->name] = $taxPrice;
                    }

                }

                $item->itemTax = $itemTaxes;
            }
            else
            {
                $item->itemTax = [];
            }
            $items[] = $item;
        }

        $purchase->itemData      = $items;
        $purchase->totalTaxPrice = $totalTaxPrice;
        $purchase->totalQuantity = $totalQuantity;
        $purchase->totalRate     = $totalRate;
        $purchase->totalDiscount = $totalDiscount;
        $purchase->taxesData     = $taxesData;


//        $logo         = asset(Storage::url('uploads/logo/'));
//        $company_logo = Utility::getValByName('company_logo_dark');
//        $img          = asset($logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png'));

        $logo         = asset(Storage::url('uploads/logo/'));
        $company_logo = Utility::getValByName('company_logo_dark');
        $purchase_logo = Utility::getValByName('purchase_logo');
        if(isset($purchase_logo) && !empty($purchase_logo))
        {
            $img = Utility::get_file('purchase_logo/') . $purchase_logo;
        }
        else{
            $img          = asset($logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png'));
        }



        if($purchase)
        {
            $color      = '#' . $settings['purchase_color'];
            $font_color = Utility::getFontColor($color);
            return view('purchase.templates.' . $settings['purchase_template'], compact('purchase', 'color', 'settings', 'vendor', 'img', 'font_color'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    public function previewPurchase($template, $color)
    {
        $objUser  = \Auth::user();
        $settings = Utility::settings();
        $purchase     = new Purchase();

        $vendor                   = new \stdClass();
        $vendor->email            = '<Email>';
        $vendor->shipping_name    = '<Vendor Name>';
        $vendor->shipping_country = '<Country>';
        $vendor->shipping_state   = '<State>';
        $vendor->shipping_city    = '<City>';
        $vendor->shipping_phone   = '<Vendor Phone Number>';
        $vendor->shipping_zip     = '<Zip>';
        $vendor->shipping_address = '<Address>';
        $vendor->billing_name     = '<Vendor Name>';
        $vendor->billing_country  = '<Country>';
        $vendor->billing_state    = '<State>';
        $vendor->billing_city     = '<City>';
        $vendor->billing_phone    = '<Vendor Phone Number>';
        $vendor->billing_zip      = '<Zip>';
        $vendor->billing_address  = '<Address>';

        $totalTaxPrice = 0;
        $taxesData     = [];
        $items         = [];
        for($i = 1; $i <= 3; $i++)
        {
            $item           = new \stdClass();
            $item->name     = 'Item ' . $i;
            $item->quantity = 1;
            $item->tax      = 5;
            $item->discount = 50;
            $item->price    = 100;

            $taxes = [
                'Tax 1',
                'Tax 2',
            ];

            $itemTaxes = [];
            foreach($taxes as $k => $tax)
            {
                $taxPrice         = 10;
                $totalTaxPrice    += $taxPrice;
                $itemTax['name']  = 'Tax ' . $k;
                $itemTax['rate']  = '10 %';
                $itemTax['price'] = '$10';
                $itemTaxes[]      = $itemTax;
                if(array_key_exists('Tax ' . $k, $taxesData))
                {
                    $taxesData['Tax ' . $k] = $taxesData['Tax 1'] + $taxPrice;
                }
                else
                {
                    $taxesData['Tax ' . $k] = $taxPrice;
                }
            }
            $item->itemTax = $itemTaxes;
            $items[]       = $item;
        }

        $purchase->purchase_id    = 1;
        $purchase->issue_date = date('Y-m-d H:i:s');
//        $purchase->due_date   = date('Y-m-d H:i:s');
        $purchase->itemData   = $items;

        $purchase->totalTaxPrice = 60;
        $purchase->totalQuantity = 3;
        $purchase->totalRate     = 300;
        $purchase->totalDiscount = 10;
        $purchase->taxesData     = $taxesData;
        $purchase->created_by     = $objUser->creatorId();

        $preview      = 1;
        $color        = '#' . $color;
        $font_color   = Utility::getFontColor($color);

        $logo         = asset(Storage::url('uploads/logo/'));
        $company_logo = Utility::getValByName('company_logo_dark');
        $settings_data = \App\Models\Utility::settingsById($purchase->created_by);
        $purchase_logo = $settings_data['purchase_logo'];

        if(isset($purchase_logo) && !empty($purchase_logo))
        {
            $img = Utility::get_file('purchase_logo/') . $purchase_logo;
        }
        else{
            $img          = asset($logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png'));
        }


        return view('purchase.templates.' . $template, compact('purchase', 'preview', 'color', 'img', 'settings', 'vendor', 'font_color'));
    }

    public function savePurchaseTemplateSettings(Request $request)
    {
        $post = $request->all();
        unset($post['_token']);

        if(isset($post['purchase_template']) && (!isset($post['purchase_color']) || empty($post['purchase_color'])))
        {
            $post['purchase_color'] = "ffffff";
        }

//
//        $validator = \Validator::make(
//            $request->all(),
//            [
//                'purchase_logo' => 'image|mimes:png|max:20480',
//            ]
//        );
//        if($validator->fails())
//        {
//            $messages = $validator->getMessageBag();
//            return  redirect()->back()->with('error', $messages->first());
//        }
//
//
//        $purchase_logo = \Auth::user()->id . '_purchase_logo.png';
//        $path = $request->file('purchase_logo')->storeAs('purchase_logo', $purchase_logo);
//        $post['purchase_logo'] = $purchase_logo;

        if($request->purchase_logo)
        {
            $dir = 'purchase_logo/';
            $purchase_logo = \Auth::user()->id . '_purchase_logo.png';
            $validation =[
                'mimes:'.'png',
                'max:'.'20480',
            ];
            $path = Utility::upload_file($request,'purchase_logo',$purchase_logo,$dir,$validation);
            if($path['flag']==0)
            {
                return redirect()->back()->with('error', __($path['msg']));
            }
            $post['purchase_logo'] = $purchase_logo;
        }


        foreach($post as $key => $data)
        {
            \DB::insert(
                'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                    $data,
                    $key,
                    \Auth::user()->creatorId(),
                ]
            );
        }

        return redirect()->back()->with('success', __('Purchase Setting updated successfully'));
    }

    public function items(Request $request)
    {

        $items = PurchaseProduct::where('purchase_id', $request->purchase_id)->where('product_id', $request->product_id)->first();
        $getGoodsRecord = $this->goodsFilter($request->purchase_id);
        return response()->json(["data" => json_encode($items),"goods" => $getGoodsRecord],200);
    }

    public function purchaseLink($purchaseId)
    {
        $id             = Crypt::decrypt($purchaseId);
        $purchase       = Purchase::find($id);

        if(!empty($purchase))
        {
            $user_id        = $purchase->created_by;
            $user           = User::find($user_id);

            $purchasePayment = PurchasePayment::where('purchase_id', $purchase->id)->first();
            $vendor = $purchase->vender;
            $iteams   = $purchase->items;

            return view('purchase.customer_bill', compact('purchase', 'vendor', 'iteams','purchasePayment','user'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

    }

    public function payment($purchase_id)
    {
        if(\Auth::user()->can('create payment purchase'))
        {
            $purchase    = Purchase::where('id', $purchase_id)->first();
            $venders = Vender::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');

            $categories = ProductServiceCategory::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $accounts   = BankAccount::select('*', \DB::raw("CONCAT(bank_name,' ',holder_name) AS name"))->where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');

            return view('purchase.payment', compact('venders', 'categories', 'accounts', 'purchase'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));

        }
    }

    public function createPayment(Request $request, $purchase_id)
    {
        if(\Auth::user()->can('create payment purchase'))
        {
            $validator = \Validator::make(
                $request->all(), [
                    'date' => 'required',
                    'amount' => 'required',
                    'account_id' => 'required',

                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $purchasePayment                 = new PurchasePayment();
            $purchasePayment->purchase_id        = $purchase_id;
            $purchasePayment->date           = $request->date;
            $purchasePayment->amount         = $request->amount;
            $purchasePayment->account_id     = $request->account_id;
            $purchasePayment->payment_method = 0;
            $purchasePayment->reference      = $request->reference;
            $purchasePayment->description    = $request->description;
            $purchasePayment->bill_no    = $request->bill_no;
            if(!empty($request->add_receipt))
            {
                $fileName = time() . "_" . $request->add_receipt->getClientOriginalName();
                $request->add_receipt->storeAs('uploads/payment', $fileName);
                $purchasePayment->add_receipt = $fileName;
            }
            $purchasePayment->save();

            $purchase  = Purchase::where('id', $purchase_id)->first();
            $due   = $purchase->getDue();
            $total = $purchase->getTotal();

            if($purchase->status == 0)
            {
                $purchase->send_date = date('Y-m-d');
                $purchase->save();
            }

            if($due <= 0)
            {
                $purchase->status = 4;
                $purchase->save();
            }
            else
            {
                $purchase->status = 3;
                $purchase->save();
            }
            $purchasePayment->user_id    = $purchase->vender_id;
            $purchasePayment->user_type  = 'Vender';
            $purchasePayment->type       = 'Partial';
            $purchasePayment->created_by = \Auth::user()->id;
            $purchasePayment->payment_id = $purchasePayment->id;
            $purchasePayment->category   = 'Bill';
            $purchasePayment->account    = $request->account_id;
            Transaction::addTransaction($purchasePayment);

            $vender = Vender::where('id', $purchase->vender_id)->first();

            $payment         = new PurchasePayment();
            $payment->name   = $vender['name'];
            $payment->method = '-';
            $payment->date   = \Auth::user()->dateFormat($request->date);
            $payment->amount = \Auth::user()->priceFormat($request->amount);
            $payment->bill   = 'bill ' . \Auth::user()->purchaseNumberFormat($purchasePayment->purchase_id);

            Utility::userBalance('vendor', $purchase->vender_id, $request->amount, 'debit');

            Utility::bankAccountBalance($request->account_id, $request->amount, 'debit');

            // Send Email
            $setings = Utility::settings();
            if($setings['new_bill_payment'] == 1)
            {

                $vender = Vender::where('id', $purchase->vender_id)->first();
                $billPaymentArr = [
                    'vender_name'   => $vender->name,
                    'vender_email'  => $vender->email,
                    'payment_name'  =>$payment->name,
                    'payment_amount'=>$payment->amount,
                    'payment_bill'  =>$payment->bill,
                    'payment_date'  =>$payment->date,
                    'payment_method'=>$payment->method,
                    'company_name'=>$payment->method,

                ];


                $resp = Utility::sendEmailTemplate('new_bill_payment', [$vender->id => $vender->email], $billPaymentArr);

                return redirect()->back()->with('success', __('Payment successfully added.') . (($resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));

            }

            return redirect()->back()->with('success', __('Payment successfully added.'));
        }

    }

    public function paymentDestroy(Request $request, $purchase_id, $payment_id)
    {

        if(\Auth::user()->can('delete payment purchase'))
        {
            $payment = PurchasePayment::find($payment_id);
            PurchasePayment::where('id', '=', $payment_id)->delete();

            $purchase = Purchase::where('id', $purchase_id)->first();

            $due   = $purchase->getDue();
            $total = $purchase->getTotal();

            if($due > 0 && $total != $due)
            {
                $purchase->status = 3;

            }
            else
            {
                $purchase->status = 2;
            }

            Utility::userBalance('vendor', $purchase->vender_id, $payment->amount, 'credit');
            Utility::bankAccountBalance($payment->account_id, $payment->amount, 'credit');

            $purchase->save();
            $type = 'Partial';
            $user = 'Vender';
            Transaction::destroyTransaction($payment_id, $type, $user);

            return redirect()->back()->with('success', __('Payment successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function vender(Request $request)
    {
        $vender = Vender::where('id', '=', $request->id)->first();

        return view('purchase.vender_detail', compact('vender'));
    }
    public function product(Request $request)
    {


        $data['product']     = $product = ProductService::find($request->product_id);
        $data['unit']        = !empty($product->unit()) ? $product->unit()->name : '';
        $data['taxRate']     = $taxRate = !empty($product->tax_id) ? $product->taxRate($product->tax_id) : 0;
        $data['taxes']       = !empty($product->tax_id) ? $product->tax($product->tax_id) : 0;
        $salePrice           = $product->purchase_price;
        $quantity            = 1;
        $taxPrice            = ($taxRate / 100) * ($salePrice * $quantity);
		$data['product_price'] = ProductPriceList::select('standard','premium','alliance')->where('product_id',$request->product_id)->first();
        $data['totalAmount'] = ($salePrice * $quantity);
		

        return json_encode($data);
    }

    public function productDestroy(Request $request)
    {
        if(\Auth::user()->can('delete purchase'))
        {
            PurchaseProduct::where('id', '=', $request->id)->delete();

            return redirect()->back()->with('success', __('Purchase product successfully deleted.'));

        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function goodsReceived($purchaseId)
    {
        $purchase     = Purchase::find($purchaseId);
        $warehouse     = warehouse::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');

        $purchase_number      = \Auth::user()->purchaseNumberFormat($purchase->id);
        $venders       = Vender::where('id', $purchase->vender_id)->get()->pluck('name', 'id');
        $product_services = ProductService::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $getGoodsRecord = $this->goodsFilter($purchaseId);
        $goodRecordsId = [];
        foreach ($getGoodsRecord as $key => $value) {
            array_push($goodRecordsId,$value['purchase_id']);
        }
        $purchaseId = $purchaseId;
        $goodRecordsId = array_unique($goodRecordsId);
        //echo '<pre>'; print_r($getGoodsRecord->toArray()); die();
        return view('purchase.good_received', compact('venders', 'product_services', 'purchase', 'warehouse','purchase_number','goodRecordsId','getGoodsRecord','purchaseId'));

    }

    public function addGoodReceived(Request $request, $purchase_id)
    {
        //echo '<pre>'; print_r($request->all()); die();
        $validator = \Validator::make(
            $request->all(), [
                'items' => 'required',
            ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $prefix = 'PGR-';
        $goods_unique_id = $prefix . random_int(100000, 999999);
        $products = $request->items;
        for($i = 0; $i < count($products); $i++)
        {
            $getmonth = date('m');
            if (date('m') <= 3) {
                $financial_year = (date('y')-1) . '-' . date('y');
            } else {
                $financial_year = date('y') . '-' . (date('y') + 1);
            }
            
            $product_services = ProductService::select('sku','quantity','product_type')->where('id', $products[$i]['product_id'])->first();
            if($product_services['product_type'] == 'barcode'){
                for ($j=1; $j <= intval($products[$i]['receiving_qty']); $j++) { 
                    $getLatest = PurchaseBarcode::select('barcode')->latest('id')->first();
                    if($getLatest != null){
                        $getCode = intval(explode('/',$getLatest['barcode'])[0]) + 1;
                    }else{
                        $getCode = 0001;
                    }
                    $countNumber = str_pad($getCode, 4, '0', STR_PAD_LEFT);
                    $generateCode = $countNumber .'/'. $getmonth . '/' . $financial_year;
                    $purchaseBarcode  = new PurchaseBarcode();
                    $purchaseBarcode->purchase_id = $purchase_id;
                    $purchaseBarcode->product_id = $products[$i]['product_id'];
                    $purchaseBarcode->purchase_product_id  = $products[$i]['id'];
                    $purchaseBarcode->barcode  = $generateCode;
                    $purchaseBarcode->sr_no  = $product_services['sku'];
                    $purchaseBarcode->created_by  = \Auth::user()->creatorId();
                    $purchaseBarcode->save();
                }
            }

            if($products[$i]['required_qty'] == $products[$i]['receiving_qty']) {
                $status  = 1;
            }elseif($products[$i]['required_qty'] >= $products[$i]['receiving_qty']){
                $status  = 2;
            }
            if(intval($products[$i]['required_qty']) < intval($products[$i]['receiving_qty'])) {
                return redirect()->back()->with('error', 'Please provide valid quantity.');
            }else{
                $findGoods = PurchaseGoodsReceived::select(DB::raw("SUM(receiving_qty) as count"))->where("purchase_product_id",$products[$i]['id'])->first();
                $getQty = intval($findGoods->count) + intval($products[$i]['receiving_qty']);

                $purchaseProduct  = new PurchaseGoodsReceived();
                $purchaseProduct->goods_unique_id = $goods_unique_id;
                $purchaseProduct->purchase_id = $purchase_id;
                $purchaseProduct->purchase_product_id  = $products[$i]['id'];
                $purchaseProduct->received_date  = date("Y-m-d H:i:s");
                $purchaseProduct->required_qty  = $products[$i]['required_qty'];
                $purchaseProduct->receiving_qty  = $products[$i]['receiving_qty'];
                $purchaseProduct->status  = $status;
                $purchaseProduct->created_by     = \Auth::user()->creatorId();
                $purchaseProduct->save();
            }

            $receveing_qut = $product_services['quantity'] + $products[$i]['receiving_qty'];
            ProductService::where('id',$products[$i]['product_id'])->update(['quantity' => $receveing_qut]);
            
        }
        return redirect()->route('purchase.index')->with('success', __('Purchase goods received successfully.'));
    }

    public function goodsRecords($purchase_id)
    {
        $purchase     = Purchase::find($purchase_id);
        $warehouse     = warehouse::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $purchase_number      = \Auth::user()->purchaseNumberFormat($purchase->id);
        $venders       = Vender::where('id', $purchase->vender_id)->get()->pluck('name', 'id');
        
        $getId = [];
        if($purchase_id == ''){
            $purchase_product = PurchaseProduct::get();
        }else{
            $purchase_product = PurchaseProduct::where('purchase_id', $purchase_id)->get();
        }
        if(count($purchase_product) > 0){
            foreach ($purchase_product as $key => $value) {
                array_push($getId,$value['id']);
            }
        }
        
        $getGoodId = PurchaseGoodsReceived::
        select('goods_unique_id','received_date','purchase_id')
        ->selectRaw('SUM(`receiving_qty`) `totalQty`')
        ->groupBy('purchase_goods_receiveds.goods_unique_id')
        ->whereIn('purchase_product_id', $getId)
        ->get();

        $getGoodsRecord = PurchaseGoodsReceived::
        select('purchase_goods_receiveds.*','purchase_products.*','product_services.name')
        ->join('purchase_products','purchase_products.id', '=' ,'purchase_goods_receiveds.purchase_product_id')
        ->join('product_services','product_services.id','=','purchase_products.product_id')
        ->whereIn('purchase_product_id', $getId)
        ->where('goods_unique_id',$getGoodId[0]['goods_unique_id'])
        ->get();
        
        return view('purchase.goods_record', compact('purchase','warehouse','purchase_number','venders','getGoodsRecord','getGoodId'));
    }

    public function goodsFilter($purchase_id = null)
    {
        $getId = [];
        if($purchase_id == null){
            $purchase_product = PurchaseProduct::get();
        }else{
            $purchase_product = PurchaseProduct::where('purchase_id', $purchase_id)->get();
        }
        if(count($purchase_product) > 0){
            foreach ($purchase_product as $key => $value) {
                array_push($getId,$value['id']);
            }
        }
        $getGoodsRecord = PurchaseGoodsReceived::
            select('purchase_goods_receiveds.*','purchase_products.*','product_services.name')
            ->selectRaw('SUM(`receiving_qty`) `totalQty`')
            ->groupBy('purchase_goods_receiveds.purchase_product_id')
            ->join('purchase_products','purchase_products.id', '=' ,'purchase_goods_receiveds.purchase_product_id')
            ->join('product_services','product_services.id','=','purchase_products.product_id')
            ->whereIn('purchase_product_id', $getId)
            ->get();
        
        return $getGoodsRecord ;
    }

    public function goodsRecordsFilter(Request $request)
    {
        $getId = [];
        $purchase_product = PurchaseProduct::where('purchase_id', $request->purchase_id)->get();
        
        if(count($purchase_product) > 0){
            foreach ($purchase_product as $key => $value) {
                array_push($getId,$value['id']);
            }
        }
        $getGoodsRecord = PurchaseGoodsReceived::
            select('purchase_goods_receiveds.*','purchase_products.*','product_services.name')
            ->join('purchase_products','purchase_products.id', '=' ,'purchase_goods_receiveds.purchase_product_id')
            ->join('product_services','product_services.id','=','purchase_products.product_id')
            ->whereIn('purchase_product_id', $getId)
            ->where('goods_unique_id', $request->good_id)
            ->get();
        return response()->json(["goods" => $getGoodsRecord],200);
    }

    public function purchaseReturnFilter($purchase_id = null)
    {
        $getId = [];
        if($purchase_id == null){
            $purchase_product = PurchaseProduct::get();
        }else{
            $purchase_product = PurchaseProduct::where('purchase_id', $purchase_id)->get();
        }
        if(count($purchase_product) > 0){
            foreach ($purchase_product as $key => $value) {
                array_push($getId,$value['id']);
            }
        }
        $getReturnRecord = PurchaseReturn::
            select('purchase_returns.*','purchase_products.*','product_services.name')
            ->selectRaw('SUM(`return_qty`) `totalReturnQty`')
            ->groupBy('purchase_returns.purchase_product_id')
            ->join('purchase_products','purchase_products.id', '=' ,'purchase_returns.purchase_product_id')
            ->join('product_services','product_services.id','=','purchase_products.product_id')
            ->whereIn('purchase_product_id', $getId)
            ->get();
        return $getReturnRecord ;
    }

    public function returnItems(Request $request)
    {

        $items = PurchaseProduct::select('purchase_goods_receiveds.receiving_qty as canReturnQty', 'purchase_products.*')
        ->join('purchase_goods_receiveds','purchase_goods_receiveds.purchase_product_id','=','purchase_products.id')
        ->where('purchase_products.purchase_id', $request->purchase_id)
        ->where('purchase_products.product_id', $request->product_id)->first();
        $getReturnRecord = $this->purchaseReturnFilter($request->purchase_id);
        return response()->json(["data" => json_encode($items),"goods" => $getReturnRecord],200);
    }

    public function purchaseReturn($purchaseId)
    {
        $purchase     = Purchase::find($purchaseId);
        $warehouse     = warehouse::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        
        $purchase_number  = \Auth::user()->purchaseNumberFormat($purchase->id);
        $venders       = Vender::where('id', $purchase->vender_id)->get()->pluck('name', 'id');
        $product_services = ProductService::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $getReturnRecord = $this->purchaseReturnFilter($purchaseId);
        $returnRecordsId = [];
        foreach ($getReturnRecord as $key => $value) {
            array_push($returnRecordsId,$value['purchase_id']);
        }
        $purchaseId = $purchaseId;
        $returnRecordsId = array_unique($returnRecordsId);
        return view('purchase.purchase_return', compact('venders', 'product_services', 'purchase', 'warehouse','purchase_number','returnRecordsId','getReturnRecord','purchaseId'));

    }

    public function addPurchaseReturn(Request $request, $purchase_id)
    {
        $validator = \Validator::make(
            $request->all(), [
                'items' => 'required',
            ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $prefix = 'PR-';
        $return_id = $prefix . random_int(100000, 999999);
        $products = $request->items;
        for($i = 0; $i < count($products); $i++)
        {
            if($products[$i]['returned_qty'] == $products[$i]['returning_qty']) {
                $status  = 1;
            }elseif($products[$i]['returned_qty'] >= $products[$i]['returning_qty']){
                $status  = 2;
            }
            if(intval($products[$i]['returned_qty']) < intval($products[$i]['returning_qty'])) {
                return redirect()->back()->with('error', 'Please provide valid quantity.');
            }else{
                $findGoods = PurchaseReturn::select(DB::raw("SUM(return_qty) as count"))->where("purchase_product_id",$products[$i]['id'])->first();
                $purchaseProduct  = new PurchaseReturn();
                $purchaseProduct->return_id = $return_id;
                $purchaseProduct->purchase_id = $purchase_id;
                $purchaseProduct->purchase_product_id  = $products[$i]['id'];
                $purchaseProduct->return_date  = date("Y-m-d H:i:s");
                $purchaseProduct->required_qty  = $products[$i]['required_qty'];
                $purchaseProduct->returned_qty  = $products[$i]['returned_qty'];
                $purchaseProduct->return_qty  = $products[$i]['returning_qty'];
                $purchaseProduct->status  = $status;
                $purchaseProduct->created_by     = \Auth::user()->creatorId();
                $purchaseProduct->save();
            }
            
        }
        return redirect()->route('purchase.index')->with('success', __('Purchase return successfully.'));
    }

    public function returnRecords($purchase_id)
    {
        $purchase     = Purchase::find($purchase_id);
        $warehouse     = warehouse::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $purchase_number      = \Auth::user()->purchaseNumberFormat($purchase->id);
        $venders       = Vender::where('id', $purchase->vender_id)->get()->pluck('name', 'id');

        $getId = [];
        if($purchase_id == ''){
            $purchase_product = PurchaseProduct::get();
        }else{
            $purchase_product = PurchaseProduct::where('purchase_id', $purchase_id)->get();
        }
        if(count($purchase_product) > 0){
            foreach ($purchase_product as $key => $value) {
                array_push($getId,$value['id']);
            }
        }
        $getPurReturnId = PurchaseReturn::
        select('return_id','return_date','purchase_id')
        ->selectRaw('SUM(`return_qty`) `totalReturnQty`')
        ->groupBy('purchase_returns.return_id')
        ->whereIn('purchase_product_id', $getId)
        ->get();
        
        $getReturnRecord = PurchaseReturn::
        select('purchase_returns.*','purchase_products.*','product_services.name')
        ->join('purchase_products','purchase_products.id', '=' ,'purchase_returns.purchase_product_id')
        ->join('product_services','product_services.id','=','purchase_products.product_id')
        ->whereIn('purchase_product_id', $getId)
        ->where('return_id',$getPurReturnId[0]['return_id'])
        ->get();

        return view('purchase.purchase_return_record', compact('purchase','warehouse','purchase_number','venders','getReturnRecord','getPurReturnId'));
    }

    public function returnRecordsFilter(Request $request)
    {
        $getId = [];
        $purchase_product = PurchaseProduct::where('purchase_id', $request->purchase_id)->get();
        
        if(count($purchase_product) > 0){
            foreach ($purchase_product as $key => $value) {
                array_push($getId,$value['id']);
            }
        }
        $getReturnRecord = PurchaseReturn::
            select('purchase_returns.*','purchase_products.*','product_services.name')
            ->join('purchase_products','purchase_products.id', '=' ,'purchase_returns.purchase_product_id')
            ->join('product_services','product_services.id','=','purchase_products.product_id')
            ->whereIn('purchase_product_id', $getId)
            ->where('return_id', $request->good_id)
            ->get();
        return response()->json(["goods" => $getReturnRecord],200);
    }

    public function goodsRecordPdf($purchase_id)
    {
        $settings = Utility::settings();
        $purchase_id   = Crypt::decrypt($purchase_id);

        $purchase     = Purchase::find($purchase_id);
        $data  = DB::table('settings');
        $data  = $data->where('created_by', '=', $purchase->created_by);
        $data1 = $data->get();

        foreach($data1 as $row)
        {
            $settings[$row->name] = $row->value;
        }

        $logo         = asset(Storage::url('uploads/logo/'));
        $company_logo = Utility::getValByName('company_logo_dark');
        $purchase_logo = Utility::getValByName('purchase_logo');
        if(isset($purchase_logo) && !empty($purchase_logo))
        {
            $img = Utility::get_file('purchase_logo/') . $purchase_logo;
        }
        else{
            $img          = asset($logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png'));
        }

        $warehouse     = warehouse::select('name')->where('created_by', \Auth::user()->creatorId())->first();
        $purchase_number      = \Auth::user()->purchaseNumberFormat($purchase->id);
        $venders       = Vender::select('name')->where('id', $purchase->vender_id)->first();
        
        $getId = [];
        if($purchase_id == ''){
            $purchase_product = PurchaseProduct::get();
        }else{
            $purchase_product = PurchaseProduct::where('purchase_id', $purchase_id)->get();
        }
        if(count($purchase_product) > 0){
            foreach ($purchase_product as $key => $value) {
                array_push($getId,$value['id']);
            }
        }
        
        $getGoodId = PurchaseGoodsReceived::
        select('goods_unique_id','received_date','purchase_id')
        ->selectRaw('SUM(`receiving_qty`) `totalQty`')
        ->groupBy('purchase_goods_receiveds.goods_unique_id')
        ->whereIn('purchase_product_id', $getId)
        ->get();

        $getGoodsRecord = PurchaseGoodsReceived::
        select('purchase_goods_receiveds.*','purchase_products.*','product_services.name')
        ->join('purchase_products','purchase_products.id', '=' ,'purchase_goods_receiveds.purchase_product_id')
        ->join('product_services','product_services.id','=','purchase_products.product_id')
        ->whereIn('purchase_product_id', $getId)
        ->get();
        
        if($purchase)
        {
            $color      = '#' . $settings['purchase_color'];
            $font_color = Utility::getFontColor($color);
            return view('purchase.pdf.good_record_temp', compact('purchase','warehouse','purchase_number','venders','getGoodsRecord','getGoodId','color', 'settings', 'img', 'font_color'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    public function goodsRecordSinglePdf($unique_id,$purchase_id)
    {
        $settings = Utility::settings();
        $purchase     = Purchase::find($purchase_id);
        $data  = DB::table('settings');
        $data  = $data->where('created_by', '=', $purchase->created_by);
        $data1 = $data->get();

        foreach($data1 as $row)
        {
            $settings[$row->name] = $row->value;
        }

        $logo         = asset(Storage::url('uploads/logo/'));
        $company_logo = Utility::getValByName('company_logo_dark');
        $purchase_logo = Utility::getValByName('purchase_logo');
        if(isset($purchase_logo) && !empty($purchase_logo))
        {
            $img = Utility::get_file('purchase_logo/') . $purchase_logo;
        }
        else{
            $img          = asset($logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png'));
        }

        $warehouse     = warehouse::select('name')->where('created_by', \Auth::user()->creatorId())->first();
        $purchase_number      = \Auth::user()->purchaseNumberFormat($purchase->id);
        $venders       = Vender::select('name')->where('id', $purchase->vender_id)->first();
        
        $getId = [];
        if($purchase_id == ''){
            $purchase_product = PurchaseProduct::get();
        }else{
            $purchase_product = PurchaseProduct::where('purchase_id', $purchase_id)->get();
        }
        if(count($purchase_product) > 0){
            foreach ($purchase_product as $key => $value) {
                array_push($getId,$value['id']);
            }
        }
        
        $getGoodId = PurchaseGoodsReceived::
        select('goods_unique_id','received_date','purchase_id')
        ->selectRaw('SUM(`receiving_qty`) `totalQty`')
        ->groupBy('purchase_goods_receiveds.goods_unique_id')
        ->whereIn('purchase_product_id', $getId)
        ->get();

        $getGoodsRecord = PurchaseGoodsReceived::
        select('purchase_goods_receiveds.*','purchase_products.*','product_services.name')
        ->join('purchase_products','purchase_products.id', '=' ,'purchase_goods_receiveds.purchase_product_id')
        ->join('product_services','product_services.id','=','purchase_products.product_id')
        ->whereIn('purchase_product_id', $getId)
        ->where('goods_unique_id', $unique_id)
        ->get();
        if($purchase)
        {
            $color      = '#' . $settings['purchase_color'];
            $font_color = Utility::getFontColor($color);
            $created_by = User::select('name')->where('id',$getGoodsRecord[0]['created_by'])->first();
            return view('purchase.pdf.good_record_temp', compact('purchase','warehouse','purchase_number','venders','getGoodsRecord','getGoodId','color', 'settings', 'img', 'font_color','created_by'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    public function purchaseRecordSinglePdf($unique_id,$purchase_id)
    {
        $settings = Utility::settings();

        $purchase     = Purchase::find($purchase_id);
        $data  = DB::table('settings');
        $data  = $data->where('created_by', '=', $purchase->created_by);
        $data1 = $data->get();

        foreach($data1 as $row)
        {
            $settings[$row->name] = $row->value;
        }

        $logo         = asset(Storage::url('uploads/logo/'));
        $company_logo = Utility::getValByName('company_logo_dark');
        $purchase_logo = Utility::getValByName('purchase_logo');
        if(isset($purchase_logo) && !empty($purchase_logo))
        {
            $img = Utility::get_file('purchase_logo/') . $purchase_logo;
        }
        else{
            $img          = asset($logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png'));
        }
        $warehouse     = warehouse::select('name')->where('created_by', \Auth::user()->creatorId())->first();
        $purchase_number      = \Auth::user()->purchaseNumberFormat($purchase->id);
        $venders       = Vender::select('name')->where('id', $purchase->vender_id)->first();

        $getId = [];
        if($purchase_id == ''){
            $purchase_product = PurchaseProduct::get();
        }else{
            $purchase_product = PurchaseProduct::where('purchase_id', $purchase_id)->get();
        }
        if(count($purchase_product) > 0){
            foreach ($purchase_product as $key => $value) {
                array_push($getId,$value['id']);
            }
        }
        $getPurReturnId = PurchaseReturn::
        select('return_id','return_date','purchase_id')
        ->selectRaw('SUM(`return_qty`) `totalReturnQty`')
        ->groupBy('purchase_returns.return_id')
        ->whereIn('purchase_product_id', $getId)
        ->get();
        
        $getReturnRecord = PurchaseReturn::
        select('purchase_returns.*','purchase_products.*','product_services.name')
        ->join('purchase_products','purchase_products.id', '=' ,'purchase_returns.purchase_product_id')
        ->join('product_services','product_services.id','=','purchase_products.product_id')
        ->whereIn('purchase_product_id', $getId)
        ->where('return_id', $unique_id)
        ->get();

        if($purchase)
        {
            $color      = '#' . $settings['purchase_color'];
            $font_color = Utility::getFontColor($color);
            $created_by = User::select('name')->where('id',$getReturnRecord[0]['created_by'])->first();
            return view('purchase.pdf.purchase_return_temp', compact('purchase','warehouse','purchase_number','venders','getReturnRecord','getPurReturnId','color', 'settings', 'img', 'font_color','created_by'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }
    public function purchaseReturnPdf($purchase_id)
    {
        $settings = Utility::settings();
        $purchase_id   = Crypt::decrypt($purchase_id);

        $purchase     = Purchase::find($purchase_id);
        $data  = DB::table('settings');
        $data  = $data->where('created_by', '=', $purchase->created_by);
        $data1 = $data->get();

        foreach($data1 as $row)
        {
            $settings[$row->name] = $row->value;
        }

        $logo         = asset(Storage::url('uploads/logo/'));
        $company_logo = Utility::getValByName('company_logo_dark');
        $purchase_logo = Utility::getValByName('purchase_logo');
        if(isset($purchase_logo) && !empty($purchase_logo))
        {
            $img = Utility::get_file('purchase_logo/') . $purchase_logo;
        }
        else{
            $img          = asset($logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png'));
        }
        $warehouse     = warehouse::select('name')->where('created_by', \Auth::user()->creatorId())->first();
        $purchase_number      = \Auth::user()->purchaseNumberFormat($purchase->id);
        $venders       = Vender::select('name')->where('id', $purchase->vender_id)->first();

        $getId = [];
        if($purchase_id == ''){
            $purchase_product = PurchaseProduct::get();
        }else{
            $purchase_product = PurchaseProduct::where('purchase_id', $purchase_id)->get();
        }
        if(count($purchase_product) > 0){
            foreach ($purchase_product as $key => $value) {
                array_push($getId,$value['id']);
            }
        }
        $getPurReturnId = PurchaseReturn::
        select('return_id','return_date','purchase_id')
        ->selectRaw('SUM(`return_qty`) `totalReturnQty`')
        ->groupBy('purchase_returns.return_id')
        ->whereIn('purchase_product_id', $getId)
        ->get();
        
        $getReturnRecord = PurchaseReturn::
        select('purchase_returns.*','purchase_products.*','product_services.name')
        ->join('purchase_products','purchase_products.id', '=' ,'purchase_returns.purchase_product_id')
        ->join('product_services','product_services.id','=','purchase_products.product_id')
        ->whereIn('purchase_product_id', $getId)
        ->get();

        if($purchase)
        {
            $color      = '#' . $settings['purchase_color'];
            $font_color = Utility::getFontColor($color);
            return view('purchase.pdf.purchase_return_temp', compact('purchase','warehouse','purchase_number','venders','getReturnRecord','getPurReturnId','color', 'settings', 'img', 'font_color'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
