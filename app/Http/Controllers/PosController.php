<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Mail\SelledInvoice;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\InvoiceProduct;
use App\Models\Pos;
use App\Models\PosReturn;
use App\Models\PosShip;
use App\Models\PosPayment;
use App\Models\PosProduct;
use App\Models\ProductService;
use App\Models\Purchase;
use App\Models\PurchaseProduct;
use App\Models\PurchasePayment;
use App\Models\PurchaseBarcode;
use App\Models\User;
use App\Models\UserClientDiscount;
use App\Models\Utility;
use App\Models\StockReport;
use App\Models\warehouse;
use App\Models\WarehouseProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class PosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->can('manage pos'))
        {
            $customers      = Customer::where('created_by', \Auth::user()->creatorId())->first();
            $warehouses = warehouse::select('*', \DB::raw("CONCAT(name) AS name"))->where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $user = Auth::user();
            $clientDiscount = UserClientDiscount::where('id', $user->client_type)->first();
            $customer_limit = [
                'advance_payment' => 'Advance Payment with ' . $customers['discount'] . '% Tread Discount',
            ];
            $treadDiscount = $customers['discount'] ;
            $details = [
                'pos_id' => $user->posNumberFormat($this->invoicePosNumber()),
                'customer' => $customers != null ? $customers->toArray() : [],
                'user' => $user != null ? $user->toArray() : [],
                'date' => date('Y-m-d'),
                'pay' => 'show',
                'clientDiscount' => $clientDiscount != null ? $clientDiscount->toArray() : [],
            ];

            return view('pos.index',compact('customers','warehouses','details','customer_limit','treadDiscount'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $sess = session()->get('pos');

        if (Auth::user()->can('manage pos') && isset($sess) && !empty($sess) && count($sess) > 0) {
            $user = Auth::user();

            $settings = Utility::settings();

            $customer = Customer::where('name', '=', $request->vc_name)->where('created_by', $user->creatorId())->first();
            $warehouse = warehouse::where('id', '=', $request->warehouse_name)->where('created_by', $user->creatorId())->first();

            $details = [
                'pos_id' => $user->posNumberFormat($this->invoicePosNumber()),
                'customer' => $customer != null ? $customer->toArray() : [],
                'warehouse' => $warehouse != null ? $warehouse->toArray() : [],
                'user' => $user != null ? $user->toArray() : [],
                'date' => date('Y-m-d'),
                'pay' => 'show',
            ];

            if (!empty($details['customer']))
            {
                // $warehousedetails = '<h7 class="text-dark">' . ucfirst($details['warehouse']['name'])  . '</p></h7>';
                $details['customer']['billing_state'] = $details['customer']['billing_state'] != '' ? ", " . $details['customer']['billing_state'] : '';
                $details['customer']['shipping_state'] = $details['customer']['shipping_state'] != '' ? ", " . $details['customer']['shipping_state'] : '';

                $customerdetails = '<h6 class="text-dark">' . ucfirst($details['customer']['name']) . '<p class="m-0 h6 font-weight-normal">' . $details['customer']['billing_phone'] . '</p>' . '<p class="m-0 h6 font-weight-normal">' . $details['customer']['billing_address'] . '</p>' . '<p class="m-0 h6 font-weight-normal">' . $details['customer']['billing_city'] . $details['customer']['billing_state'] . '</p>' . '<p class="m-0 h6 font-weight-normal">' . $details['customer']['billing_country'] . '</p>' . '<p class="m-0 h6 font-weight-normal">' . $details['customer']['billing_zip'] . '</p></h6>';

                $shippdetails = '<h6 class="text-dark"><b>' . ucfirst($details['customer']['name']) . '</b>' . '<p class="m-0 h6 font-weight-normal">' . $details['customer']['shipping_phone'] . '</p>' . '<p class="m-0 h6 font-weight-normal">' . $details['customer']['shipping_address'] . '</p>' . '<p class="m-0 h6 font-weight-normal">' . $details['customer']['shipping_city'] . $details['customer']['shipping_state'] . '</p>' . '<p class="m-0 h6 font-weight-normal">' . $details['customer']['shipping_country'] . '</p>' . '<p class="m-0 h6 font-weight-normal">' . $details['customer']['shipping_zip'] . '</p></h6>';

            }
            else {
                $customerdetails = '<h2 class="h6"><b>' . __('Walk-in Customer') . '</b><h2>';
                // $warehousedetails = '<h7 class="text-dark">' . ucfirst($details['warehouse']['name'])  . '</p></h7>';
                $shippdetails = '-';

            }


            $settings['company_telephone'] = $settings['company_telephone'] != '' ? ", " . $settings['company_telephone'] : '';
            $settings['company_state']     = $settings['company_state'] != '' ? ", " . $settings['company_state'] : '';

            $userdetails = '<h6 class="text-dark"><b>' . ucfirst($details['user']['name']) . ' </b> <h2  class="font-weight-normal">' . '<p class="m-0 font-weight-normal">' . $settings['company_name'] . $settings['company_telephone'] . '</p>' . '<p class="m-0 font-weight-normal">' . $settings['company_address'] . '</p>' . '<p class="m-0 h6 font-weight-normal">' . $settings['company_city'] . $settings['company_state'] . '</p>' . '<p class="m-0 font-weight-normal">' . $settings['company_country'] . '</p>' . '<p class="m-0 font-weight-normal">' . $settings['company_zipcode'] . '</p></h2>';

            $details['customer']['details'] = $customerdetails;

            $details['customer']['shippdetails'] = $shippdetails;
            
            $details['user']['details'] = $userdetails;
            
            $mainsubtotal = 0;
            $sales        = [];
            $discount = 0;
            
            foreach ($sess as $key => $value) {
                $subtotal = $value['price'] * $value['quantity'];
                $tax      = ($subtotal * $value['tax']) / 100;
                
                $sales['data'][$key]['name']       = $value['name'];
                $sales['data'][$key]['quantity']   = $value['quantity'];
                $sales['data'][$key]['price']      = Auth::user()->priceFormat($value['price']);
                $sales['data'][$key]['priceTotal'] = Auth::user()->priceFormat((int)$value['price'] * (int)$value['quantity']);
                $sales['data'][$key]['tax']        = $value['tax'] . '%';
                $sales['data'][$key]['tax_amount'] = Auth::user()->priceFormat($tax);
                $sales['data'][$key]['subtotal']   = Auth::user()->priceFormat($value['subtotal']);
                $discount                           += $value['discount'];
                $mainsubtotal                      += $value['subtotal'];
            }

            $sales['discount'] = Auth::user()->priceFormat($discount);
            $total= $mainsubtotal;
            $sales['sub_total'] = Auth::user()->priceFormat($mainsubtotal);
            $sales['total'] = Auth::user()->priceFormat($total);

            return view('pos.show', compact('sales', 'details'));
        } else {
            return response()->json(
                [
                    'error' => __('Add some products to cart!'),
                ],
                '404'
            );
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
        if (Auth::user()->can('manage pos')) {
            $user_id = Auth::user()->id;
            $customer_id      = Customer::customer_id($request->vc_name);
            $pos_id       = $this->invoicePosNumber();
            $sales            = session()->get('pos');

            if (isset($sales) && !empty($sales) && count($sales) > 0) {
                $result = DB::table('pos')->where('pos_id', $pos_id)->where('created_by', $user_id)->get();
                // if (count($result) > 0) {
                //     return response()->json(
                //         [
                //             'code' => 200,
                //             'success' => __('Payment is already completed!'),
                //         ]
                //     );
                // } else {
                    $pos_date = '';
                    $clientDiscount = UserClientDiscount::select('payment')->where('id', Auth::user()->client_type)->first();
                    $day = (int)$clientDiscount['payment'];
                    $pos_date = date('Y-m-d',strtotime('+' . $day . 'days'));
                    
                    $pos = new Pos();
                    $pos->pos_id       = $pos_id;
                    $pos->customer_id      = $customer_id;
                    $pos->created_by       = $user_id;
                    $pos->pos_date       = $pos_date;
                    $pos->customer_limit   = $request->customer_limit;
                    $pos->save();

                    foreach ($sales as $key => $value) {
                        $product_id = $value['id'];

                        $product = ProductService::whereId($product_id)->where('created_by', $user_id)->first();

                        $original_quantity = ($product == null) ? 0 : (int)$product->quantity;

                        $product_quantity = $original_quantity - $value['quantity'];


                        if ($product != null && !empty($product)) {
                            ProductService::where('id', $product_id)->update(['quantity' => $product_quantity]);
                        }

                        $tax_id = ProductService::tax_id($product_id);

                        $positems = new PosProduct();
                        $positems->pos_id    = $pos->id;
                        $positems->product_id = $product_id;
                        $positems->price      = $value['price'];
                        $positems->quantity   = $value['quantity'];
                        $positems->tax     = $tax_id;
                        // $positems->tax        = $value['tax'];
                        $positems->save();
                    }

                    $posPayment                 = new PosPayment();
                    $posPayment->pos_id          =$pos->id;
                    $posPayment->date           = $request->date;

                    $discount = 0;
                    $mainsubtotal = 0;
                    $sales        = [];

                    $sess = session()->get('pos');

                    foreach ($sess as $key => $value) {
                        $subtotal = $value['price'] * $value['quantity'];
                        $tax      = ($subtotal * $value['tax']) / 100;
                        $sales['data'][$key]['price']      = Auth::user()->priceFormat($value['price']);
                        $sales['data'][$key]['tax']        = $value['tax'] . '%';
                        $sales['data'][$key]['tax_amount'] = Auth::user()->priceFormat($tax);
                        $sales['data'][$key]['subtotal']   = Auth::user()->priceFormat($value['subtotal']);
                        $discount                           += $value['discount'];
                        $mainsubtotal                      += $value['subtotal'];
                    }
                    $amount = $mainsubtotal;
                    $posPayment->amount         = $amount;
                    $posPayment->discount         = $discount;
                    $posPayment->discount_amount       = $amount;
                    $posPayment->save();

                    session()->forget('pos');

                    return response()->json(
                        [
                            'code' => 200,
                            'success' => __('Payment completed successfully!'),
                        ]
                    );
                // }
            } else {
                return response()->json(
                    [
                        'code' => 404,
                        'success' => __('Items not found!'),
                    ]
                );
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function edit($ids)
    {
        $id = Crypt::decrypt($ids);
        $pos = Pos::find($id);

        $pos_number = \Auth::user()->posNumberFormat($pos->id);
        $customers = Customer::where('customer_id', $pos->customer_id)->get()->pluck('name', 'id');
        $posProduct = PosProduct::where('pos_id', $id)->get();
        $user = User::where('id',$pos->created_by)->first();
        $clientDiscount = UserClientDiscount::where('id', $user->client_type)->first();
        $customer_limit = [
            'credit_line' => 'Credit Line (' . $user->client_credit_line .')',
            'advance_payment' => 'Advance Payment with ' . $clientDiscount['tread_discount'] . '% Tread Discount',
        ];
        $treadDiscount = $clientDiscount['tread_discount'] ;
        $product_id = [];
        foreach ($posProduct as $key => $value) {
            array_push($product_id,$value['product_id']);
        }
        $iteams      = $pos->items;
        $product_services = ProductService::whereIn('id', $product_id)->get()->pluck('name', 'id');
        return view('pos.edit', compact('posProduct','customers', 'product_services', 'pos', 'pos_number','customer_limit','iteams','treadDiscount'));
    }
    
    public function items(Request $request)
    {
        $items = PosProduct::
        select('pos_payments.discount as totalDiscount','pos_products.*')
        ->join('pos_payments','pos_payments.pos_id', '=','pos_products.pos_id')
        ->where('pos_products.pos_id', $request->pos_id)->where('pos_products.product_id', $request->product_id)->first();

        return json_encode($items);
    }

    public function update(Request $request,$posId)
    {
        $products = $request->items;
        $totalAmount = [];

        $mainsubtotal = 0;
        $discount = 0;
        $tax = 0;
        $pos = Pos::where('id',$posId)->update(['customer_limit' => $request->customer_limit]);
        $posPayment   = PosPayment::where('pos_id',$posId)->first();
        for ($i = 0; $i < count($products); $i++) {
            $posProduct = PosProduct::find($products[$i]['id']);
            if (isset($products[$i]['item'])) {
                $posProduct->product_id = $products[$i]['item'];
            }
            $amount = $products[$i]['price'] * $products[$i]['quantity'];
            array_push($totalAmount,$amount);

            $posProduct->quantity    = $products[$i]['quantity'];
            $posProduct->tax         = $products[$i]['tax'];
            $posProduct->discount    = $products[$i]['discount'];
            $posProduct->price       = $products[$i]['price'];
            $posProduct->description = $products[$i]['description'];
            $posProduct->save();

            $subtotal = $products[$i]['price'] * $products[$i]['quantity'];
            $tax      += $products[$i]['itemTaxPrice'];
            $discount += $products[$i]['discount'];
            $mainsubtotal  += $subtotal;
        }

        $amount = $tax + $mainsubtotal;
        $posPayment->amount         = $amount;
        $total= $amount - $discount;
        $posPayment->discount         = $discount;
        $posPayment->discount_amount       = $total;
        $posPayment->save();

        return redirect()->route('pos.report')->with('success', __('Sales successfully updated.'));
            
    }

    function invoicePosNumber()
    {
        if (Auth::user()->can('manage pos')) {
            $latest = Pos::latest()->first();

            return $latest ? $latest->pos_id + 1 : 1;
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    function report()
    {
        if(\Auth::user()->can('manage pos'))
        {
            $returnRecordsId = [];
            $returnCompleted = [];
            $invoiceStatus = [];
            $shippingStatus = [];
            $deliveryStatus = [];
            if(\Auth::user()->type == 'customer'){
                $posPayments = Pos::where('created_by', '=', \Auth::user()->id)->get();
            }else{
                $posPayments = Pos::get();

                $deliveryStatus = PosShip::select('pos_id','status')->get();
                //Check invoice added or not
                $invoiceId = Invoice::select('pos_id')->where('pos_id', '!=', 0)->get();
                foreach ($invoiceId as $key => $value) {
                    array_push($invoiceStatus,$value['pos_id']);
                }

                //Check shipping added or not
                $shipId = PosShip::select('pos_id')->get();
                
                foreach ($shipId as $key => $value) {
                    array_push($shippingStatus,$value['pos_id']);
                }
                $getReturnRecord = $this->posReturnFilter();
                if($getReturnRecord != null){
                    foreach ($getReturnRecord as $key => $value) {
                        if($value['required_qty'] == $value['totalReturnQty']){
                            array_push($returnCompleted,$value['pos_id']);
                        }
                        array_push($returnRecordsId,$value['pos_id']);
                    }
                    $returnCompleted = array_unique($returnCompleted);
                    $returnRecordsId = array_unique($returnRecordsId);
                }
            }
            return view('pos.report',compact('posPayments','invoiceStatus','shippingStatus','returnRecordsId','returnCompleted','deliveryStatus'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

    }

    function barcode()
    {
        if(\Auth::user()->can('manage pos'))
        {
            $purchase = Purchase::where('created_by', '=', \Auth::user()->creatorId())->get();
            // $productServices = ProductService::where('created_by', '=', \Auth::user()->creatorId())->get();
            // $barcode  = [
            //     'barcodeType' => Auth::user()->barcodeType() ,
            //     'barcodeFormat' => Auth::user()->barcodeFormat(),
            // ];

            return view('pos.barcode',compact('purchase'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

    }

    public function setting()
    {
        if(\Auth::user()->can('manage pos'))
        {
            $settings                = Utility::settings();

            return view('pos.setting',compact('settings'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }


    }

    public function BarcodesettingStore(Request $request)
    {

        $request->validate(
                [

                    'barcode_type' => 'required',
                    'barcode_format' => 'required',
                ]
            );

        $post['barcode_type'] = $request->barcode_type;
        $post['barcode_format'] = $request->barcode_format;

        foreach($post as $key => $data)
        {

            $arr = [
                $data,
                $key,
                \Auth::user()->id,
            ];

            \DB::insert(
                'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', $arr
            );
        }
        return redirect()->back()->with('success', 'Barcode setting successfully updated.');

    }

    public function printBarcode($id)
    {
        if(\Auth::user()->can('manage pos'))
        {
            $barcode  = [
                'barcodeType' => Auth::user()->barcodeType() ,
                'barcodeFormat' => Auth::user()->barcodeFormat(),
            ];
            // $warehouses = warehouse::select('*', \DB::raw("CONCAT(name) AS name"))->where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $purchase = PurchaseBarcode::where('purchase_id',$id)->get();
            return view('pos.print',compact('purchase','barcode'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

    }

    public function getproduct(Request $request)
    {
//        dd($request->all());
        if($request->warehouse_id == 0)
        {
            $productServices = WarehouseProduct::where('product_id', '=', $request->warehouse_id)->where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id')->toArray();
        }
        else
        {
            $productServicesId = WarehouseProduct::where('created_by', '=', \Auth::user()->creatorId())->where('warehouse_id', $request->warehouse_id)->get()->pluck('product_id')->toArray();
            $productServices = ProductService::whereIn('id', $productServicesId )->get()->pluck('name', 'id')->toArray();
        }

        return response()->json($productServices);
    }

    public function receipt(Request $request)
    {

        if(!empty($request->product_id))
        {
            $productServices = ProductService::whereIn('id',$request->product_id)->get();
            $quantity  = $request->quantity;
            $barcode  = [
                'barcodeType' => Auth::user()->barcodeType() == '' ? 'code128' : Auth::user()->barcodeType(),
                'barcodeFormat' => Auth::user()->barcodeFormat() == '' ? 'css' : Auth::user()->barcodeFormat(),
            ];
        }
        else
        {
            return redirect()->back()->with('error', 'Product is required.');

        }

        return view('pos.receipt',compact('productServices','barcode','quantity'));

    }


    public function cartdiscount(Request $request){
        $sess = session()->get('pos');
        $subtotal = array_sum(array_column($sess, 'subtotal'));
        $discount = $request->discount;
        $total = $subtotal - $discount;
        $total = User::priceFormats($total);

        return response()->json(['total' => $total], '200');


    }

    public function posSalesApprove($id)
    {
        $posProduct = PosProduct::where('pos_id', $id)->update(["approve_status" => 1]);

        $pos = Pos::where('id', $id)->first();
        $pos->status    = 1;
        $pos->save();
        return redirect()->back()->with('success', __('Sale order approved successfully.'));
    }

    public function posSalesCancel($id)
    {
        $posProduct = PosProduct::where('pos_id', $id)->update(["approve_status" => 2]);
        $pos = Pos::where('id', $id)->update(["status" => 2]);
        
        return redirect()->back()->with('success', __('Sale order cancel successfully.'));
    }

    public function showPosInvoice($id)
    {
        $id = Crypt::decrypt($id);
        $pos = Pos::find($id);
        $posPayment = PosPayment::where('pos_id', $pos->pos_id)->first();
        $vendor      = $pos->customer;
        $iteams      = $pos->items;
        return view('pos.view', compact('pos', 'vendor', 'iteams', 'posPayment'));
        
    }

    public function posPdf($purchase_id)
    {
        $settings = Utility::settings();
        $purchaseId   = Crypt::decrypt($purchase_id);

        $purchase  = Pos::where('id', $purchaseId)->first();
        $data  = DB::table('settings');
        $data  = $data->where('created_by', '=', $purchase->created_by);
        $data1 = $data->get();

        foreach($data1 as $row)
        {
            $settings[$row->name] = $row->value;
        }

        $vendor = $purchase->customer;

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
            return view('pos.templates.' . $settings['purchase_template'], compact('purchase', 'color', 'settings', 'vendor', 'img', 'font_color'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    public function showApproveDetail($id)
    {
        $id = Crypt::decrypt($id);
        $pos = Pos::find($id);
        $posPayment = PosPayment::where('pos_id', $pos->pos_id)->first();
        $vendor      = $pos->customer;
        $iteams      = $pos->items;
        
        return view('pos.showApprove', compact('pos', 'vendor', 'iteams', 'posPayment'));
    }

    public function payment($pos_id)
    {
        if(\Auth::user()->can('create payment purchase'))
        {
            $pos    = Pos::where('id', $pos_id)->first();
            $customers = Customer::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');

            $accounts   = BankAccount::select('*', \DB::raw("CONCAT(bank_name,' ',holder_name) AS name"))->where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');

            return view('pos.payment', compact('customers', 'accounts', 'pos'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));

        }
    }

    public function createPayment(Request $request, $pos_id)
    {
        if(\Auth::user()->can('create invoice'))
        {
            $posPayment = Pos::where('id', $pos_id)->first();
            $status = Invoice::$statues;

            $invoice                 = new Invoice();
            $invoice->invoice_id     = $this->invoiceNumber();
            $invoice->customer_id    = $posPayment->customer_id;
            $invoice->pos_id         = $pos_id;
            $invoice->status         = 0;
            $invoice->issue_date     = date('Y-m-d');
            $invoice->due_date       = date('Y-m-d');
            $invoice->category_id    = 0;
            $invoice->ref_number     = $request->reference;
            $invoice->created_by     = \Auth::user()->creatorId();
            $invoice->save();
            $products = posProduct::where('pos_id', $pos_id)->get();;

            for($i = 0; $i < count($products); $i++)
            {
                $invoiceProduct              = new InvoiceProduct();
                $invoiceProduct->invoice_id  = $invoice->id;
                $invoiceProduct->product_id  = $products[$i]['product_id'];
                $invoiceProduct->quantity    = $products[$i]['quantity'];
                $invoiceProduct->tax         = $products[$i]['tax'];
                $invoiceProduct->discount    = $products[$i]['discount'];
                $invoiceProduct->price       = $products[$i]['price'];
                $invoiceProduct->description = $products[$i]['description'];
                $invoiceProduct->save();

                //inventory management (Quantity)
                Utility::total_quantity('minus',$invoiceProduct->quantity,$invoiceProduct->product_id);

                //Slack Notification
                $setting  = Utility::settings(\Auth::user()->creatorId());
                if(isset($setting['invoice_notification']) && $setting['invoice_notification'] ==1){
                    $msg = __("New Invoice").' '. \Auth::user()->invoiceNumberFormat($invoice->invoice_id).' '. __("created by").' ' .\Auth::user()->name.'.';
                    Utility::send_slack_msg($msg);
                }

                //Telegram Notification
                $setting  = Utility::settings(\Auth::user()->creatorId());
                if(isset($setting['telegram_invoice_notification']) && $setting['telegram_invoice_notification'] ==1){
                    $msg = __("New Invoice").' '. \Auth::user()->invoiceNumberFormat($invoice->invoice_id).' '. __("created by").' ' .\Auth::user()->name.'.';
                    Utility::send_telegram_msg($msg);
                }
            }

            //Twilio Notification
            $setting  = Utility::settings(\Auth::user()->creatorId());
            $customer = Customer::find($posPayment->customer_id);
            if(isset($setting['twilio_invoice_notification']) && $setting['twilio_invoice_notification'] ==1)
            {
                $msg = __("New Invoice").' '. \Auth::user()->invoiceNumberFormat($invoice->invoice_id).' '. __("created by").' ' .\Auth::user()->name.'.';
                Utility::send_twilio_msg($customer->contact,$msg);
            }

            //Product Stock Report
            $type='invoice';
            $type_id = $invoice->id;
            StockReport::where('type','=','invoice')->where('type_id' ,'=', $invoice->id)->delete();
            $description=$invoiceProduct->quantity.'  '.__(' quantity sold in invoice').' '. \Auth::user()->invoiceNumberFormat($invoice->invoice_id);
            Utility::addProductStock( $invoiceProduct->product_id,$invoiceProduct->quantity,$type,$description,$type_id);

            return redirect()->back()->with('success', __('Payment done successfully.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    function invoiceNumber()
    {
        $latest = Invoice::where('created_by', '=', \Auth::user()->creatorId())->latest()->first();
        if(!$latest)
        {
            return 1;
        }

        return $latest->invoice_id + 1;
    }

    public function PosReturn($posId)
    {
        $pos     = Pos::find($posId);
        $warehouse     = warehouse::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        
        $pos_number  = \Auth::user()->posNumberFormat($pos->id);
        $customers = Customer::where('id', $pos->customer_id)->get()->pluck('name', 'id');
        $product_services = ProductService::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $getReturnRecord = $this->posReturnFilter($posId);
        $returnRecordsId = [];
        if($getReturnRecord != null){
            foreach ($getReturnRecord as $key => $value) {
                array_push($returnRecordsId,$value['pos_id']);
            }
        }
        $posId = $posId;
        $returnRecordsId = array_unique($returnRecordsId);
        return view('pos.sales_return', compact('customers', 'product_services', 'pos', 'warehouse','pos_number','returnRecordsId','getReturnRecord','posId'));

    }

    public function addPosReturn(Request $request, $pos_id)
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
        $prefix = 'SR-';
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
                $findGoods = PosReturn::select(DB::raw("SUM(return_qty) as count"))->where("pos_product_id",$products[$i]['id'])->first();
                $posProduct  = new PosReturn();
                $posProduct->return_id = $return_id;
                $posProduct->pos_id = $pos_id;
                $posProduct->pos_product_id  = $products[$i]['id'];
                $posProduct->return_date  = date("Y-m-d H:i:s");
                $posProduct->required_qty  = $products[$i]['required_qty'];
                $posProduct->returned_qty  = $products[$i]['returned_qty'];
                $posProduct->return_qty  = $products[$i]['returning_qty'];
                $posProduct->status  = $status;
                $posProduct->created_by     = \Auth::user()->creatorId();
                $posProduct->save();
            }
            
        }
        return redirect()->route('pos.report')->with('success', __('Sales return successfully.'));
    }

    public function returnRecords($pos_id)
    {
        $pos     = Pos::find($pos_id);
        $warehouse     = warehouse::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $pos_number      = \Auth::user()->posNumberFormat($pos->id);
        $customers = Customer::where('id', $pos->customer_id)->get()->pluck('name', 'id');
        $getId = [];
        if($pos_id == ''){
            $pos_product = PosProduct::get();
        }else{
            $pos_product = PosProduct::where('pos_id', $pos_id)->get();
        }
        if(count($pos_product) > 0){
            foreach ($pos_product as $key => $value) {
                array_push($getId,$value['id']);
            }
        }
        $getPurReturnId = PosReturn::
        select('return_id','return_date','pos_id')
        ->selectRaw('SUM(`return_qty`) `totalReturnQty`')
        ->groupBy('pos_returns.return_id')
        ->whereIn('pos_product_id', $getId)
        ->get();
        
        $getReturnRecord = PosReturn::
        select('pos_returns.*','pos_products.*','product_services.name')
        ->join('pos_products','pos_products.id', '=' ,'pos_returns.pos_product_id')
        ->join('product_services','product_services.id','=','pos_products.product_id')
        ->whereIn('pos_product_id', $getId)
        ->where('return_id',$getPurReturnId[0]['return_id'])
        ->get();

        return view('pos.sales_return_record', compact('pos','warehouse','pos_number','customers','getReturnRecord','getPurReturnId'));
    }

    public function returnItems(Request $request)
    {
        $items = PosProduct::select('pos_products.*')
        ->where('pos_products.pos_id', $request->pos_id)
        ->where('pos_products.product_id', $request->product_id)->first();
        $getReturnRecord = $this->posReturnFilter($request->pos_id);
        return response()->json(["data" => json_encode($items),"goods" => $getReturnRecord],200);
    }

    public function returnRecordsFilter(Request $request)
    {
        $getId = [];
        $pos_product = PosProduct::where('pos_id', $request->pos_id)->get();
        
        if(count($pos_product) > 0){
            foreach ($pos_product as $key => $value) {
                array_push($getId,$value['id']);
            }
        }
        $getReturnRecord = PosReturn::
            select('pos_returns.*','pos_products.*','product_services.name')
            ->join('pos_products','pos_products.id', '=' ,'pos_returns.pos_product_id')
            ->join('product_services','product_services.id','=','pos_products.product_id')
            ->whereIn('pos_product_id', $getId)
            ->where('return_id', $request->good_id)
            ->get();
        return response()->json(["goods" => $getReturnRecord],200);
    }

    public function posReturnFilter($pos_id = null)
    {
        $getId = [];
        if($pos_id == null){
            $pos_product = PosProduct::get();
        }else{
            $pos_product = PosProduct::where('pos_id', $pos_id)->get();
        }
        if(count($pos_product) > 0){
            foreach ($pos_product as $key => $value) {
                array_push($getId,$value['id']);
            }
        }
        $getReturnRecord = PosReturn::
            select('pos_returns.*','pos_products.*','product_services.name')
            ->selectRaw('SUM(`return_qty`) `totalReturnQty`')
            ->groupBy('pos_returns.pos_product_id')
            ->join('pos_products','pos_products.id', '=' ,'pos_returns.pos_product_id')
            ->join('product_services','product_services.id','=','pos_products.product_id')
            ->whereIn('pos_product_id', $getId)
            ->get();
        return $getReturnRecord ;
    }

    public function posReturnPdf($pos_id)
    {
        $settings = Utility::settings();
        $pos_id   = Crypt::decrypt($pos_id);

        $pos     = Pos::find($pos_id);
        $data  = DB::table('settings');
        $data  = $data->where('created_by', '=', $pos->created_by);
        $data1 = $data->get();

        foreach($data1 as $row)
        {
            $settings[$row->name] = $row->value;
        }

        $logo         = asset(Storage::url('uploads/logo/'));
        $company_logo = Utility::getValByName('company_logo_dark');
        $pos_logo = Utility::getValByName('pos_logo');
        if(isset($pos_logo) && !empty($pos_logo))
        {
            $img = Utility::get_file('pos_logo/') . $pos_logo;
        }
        else{
            $img          = asset($logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png'));
        }
        $warehouse     = warehouse::select('name')->where('created_by', \Auth::user()->creatorId())->first();
        $pos_number      = \Auth::user()->posNumberFormat($pos->id);
        $customers = Customer::select('name')->where('id', $pos->customer_id)->first();
        $getId = [];
        if($pos_id == ''){
            $pos_product = PosProduct::get();
        }else{
            $pos_product = PosProduct::where('pos_id', $pos_id)->get();
        }
        if(count($pos_product) > 0){
            foreach ($pos_product as $key => $value) {
                array_push($getId,$value['id']);
            }
        }
        $getPurReturnId = PosReturn::
        select('return_id','return_date','pos_id')
        ->selectRaw('SUM(`return_qty`) `totalReturnQty`')
        ->groupBy('pos_returns.return_id')
        ->whereIn('pos_product_id', $getId)
        ->get();
        
        $getReturnRecord = PosReturn::
        select('pos_returns.*','pos_products.*','product_services.name')
        ->join('pos_products','pos_products.id', '=' ,'pos_returns.pos_product_id')
        ->join('product_services','product_services.id','=','pos_products.product_id')
        ->whereIn('pos_product_id', $getId)
        ->get();

        if($pos)
        {
            $color      = '#' . $settings['purchase_color'];
            $font_color = Utility::getFontColor($color);
            $created_by = User::select('name')->where('id',$getReturnRecord[0]['created_by'])->first();
            return view('pos.pdf.pos_return_temp', compact('pos','warehouse','pos_number','customers','getReturnRecord','getPurReturnId','color', 'settings', 'img', 'font_color','created_by'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function posReturnSinglePdf($unique_id,$pos_id)
    {
        $settings = Utility::settings();

        $pos     = Pos::find($pos_id);
        $data  = DB::table('settings');
        $data  = $data->where('created_by', '=', $pos->created_by);
        $data1 = $data->get();

        foreach($data1 as $row)
        {
            $settings[$row->name] = $row->value;
        }

        $logo         = asset(Storage::url('uploads/logo/'));
        $company_logo = Utility::getValByName('company_logo_dark');
        $pos_logo = Utility::getValByName('pos_logo');
        if(isset($pos_logo) && !empty($pos_logo))
        {
            $img = Utility::get_file('pos_logo/') . $pos_logo;
        }
        else{
            $img          = asset($logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png'));
        }
        $warehouse     = warehouse::select('name')->where('created_by', \Auth::user()->creatorId())->first();
        $pos_number      = \Auth::user()->posNumberFormat($pos->id);
        $customers = Customer::select('name')->where('id', $pos->customer_id)->first();
        $getId = [];
        if($pos_id == ''){
            $pos_product = PosProduct::get();
        }else{
            $pos_product = PosProduct::where('pos_id', $pos_id)->get();
        }
        if(count($pos_product) > 0){
            foreach ($pos_product as $key => $value) {
                array_push($getId,$value['id']);
            }
        }
        $getPurReturnId = PosReturn::
        select('return_id','return_date','pos_id')
        ->selectRaw('SUM(`return_qty`) `totalReturnQty`')
        ->groupBy('pos_returns.return_id')
        ->whereIn('pos_product_id', $getId)
        ->get();
        
        $getReturnRecord = PosReturn::
        select('pos_returns.*','pos_products.*','product_services.name')
        ->join('pos_products','pos_products.id', '=' ,'pos_returns.pos_product_id')
        ->join('product_services','product_services.id','=','pos_products.product_id')
        ->whereIn('pos_product_id', $getId)
        ->where('return_id', $unique_id)
        ->get();

        if($pos)
        {
            $color      = '#' . $settings['purchase_color'];
            $font_color = Utility::getFontColor($color);
            $created_by = User::select('name')->where('id',$getReturnRecord[0]['created_by'])->first();
            return view('pos.pdf.pos_return_temp', compact('pos','warehouse','pos_number','customers','getReturnRecord','getPurReturnId','color', 'settings', 'img', 'font_color','created_by'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function posShipPdf($ship_id)
    {
        $settings = Utility::settings();
        $ship_id   = Crypt::decrypt($ship_id);
        $pos     = PosShip::with('shipDetail')->where('id', $ship_id)->first();
        $data  = DB::table('settings');
        $data  = $data->where('created_by', '=', $pos->created_by);
        $data1 = $data->get();

        foreach($data1 as $row)
        {
            $settings[$row->name] = $row->value;
        }

        $logo         = asset(Storage::url('uploads/logo/'));
        $company_logo = Utility::getValByName('company_logo_dark');
        $pos_logo = Utility::getValByName('pos_logo');
        if(isset($pos_logo) && !empty($pos_logo))
        {
            $img = Utility::get_file('pos_logo/') . $pos_logo;
        }
        else{
            $img          = asset($logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png'));
        }
        $warehouse     = warehouse::select('name')->where('created_by', $pos->created_by)->first();
        $pos_number      = $pos->ship_unique;
        $customers = Customer::where('id', $pos->customer_id)->first();
        $getId = [];
        if($ship_id == ''){
            $pos_product = PosProduct::get();
        }else{
            $pos_product = PosProduct::where('pos_id', $pos->pos_id)->get();
        }
        
        if($pos)
        {
            $color      = '#' . $settings['purchase_color'];
            $font_color = Utility::getFontColor($color);
            $created_by = User::select('name')->where('id',$pos->created_by)->first();
            return view('ship.ship_pdf', compact('pos','warehouse','pos_product','pos_number','customers','color', 'settings', 'img', 'font_color','created_by'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function getBarcodePrint()
    {
        return view('pos.get_barcode_print');
    }

    public function findBarcode(Request $request)
    {
        $request->validate(
            [
                'serial_no' => 'required',
            ]
        );
       
        $barcode  = [
            'barcodeType' => Auth::user()->barcodeType() ,
            'barcodeFormat' => Auth::user()->barcodeFormat(),
        ];
        $purchase = PurchaseBarcode::where('barcode',$request->serial_no)->get();
        return view('pos.print',compact('purchase','barcode'));

    }
}
