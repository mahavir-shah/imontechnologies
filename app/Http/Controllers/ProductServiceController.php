<?php

namespace App\Http\Controllers;

use App\Models\CustomField;
use App\Models\UserClientDiscount;
use App\Models\ProductPriceList;
use App\Exports\ProductServiceExport;
use App\Imports\ProductServiceImport;
use App\Models\Product;
use App\Models\ProductKit;
use App\Models\ProductService;
use App\Models\PurchaseBarcode;
use App\Models\ProductServiceCategory;
use App\Models\ProductServiceUnit;
use App\Models\PosProduct;
use App\Models\Tax;
use App\Models\User;
use App\Models\Utility;
use App\Models\Vender;
use App\Models\WarehouseProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;



class ProductServiceController extends Controller
{
    public function index(Request $request)
    {

        $category = ProductServiceCategory::where('created_by', '=', \Auth::user()->creatorId())->where('type', '=', 0)->get()->pluck('name', 'id');
        $category->prepend('Select Category', '');

        if(!empty($request->category))
        {

            $productServices = ProductService::where('created_by', '=', \Auth::user()->creatorId())->where('category_id', $request->category)->get();
        }
        else
        {
            $productServices = ProductService::where('created_by', '=', \Auth::user()->creatorId())->get();
        }

        return view('productservice.index', compact('productServices', 'category'));
       
    }


    public function create()
    {
        $customFields = CustomField::where('created_by', '=', \Auth::user()->creatorId())->where('module', '=', 'product')->get();
        $unit         = ProductServiceUnit::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $tax          = Tax::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');

        return view('productservice.create', compact('unit', 'tax', 'customFields'));
    }

    public function store(Request $request)
    {
        echo '<pre>'; print_r($request->all()); die();
            $rules = [
                'name' => 'required',
                'sku' => 'required|unique:product_services,sku',
                'purchase_price' => 'required|numeric',
                'unit_id' => 'required',
                'product_type' => 'required',
                'hsn_code' => 'required',
                'low_stock_notification' => 'required|numeric',
//                'pro_image' => 'mimes:jpeg,png,jpg,gif,pdf,doc,zip|max:20480',
            ];

            $validator = \Validator::make($request->all(), $rules);

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('productservice.index')->with('error', $messages->first());
            }

            $productService                 = new ProductService();
            $productService->name           = $request->name;
            $productService->description    = $request->description;
            $productService->sku            = $request->sku;
            $productService->purchase_price = $request->purchase_price;
            $productService->tax_id         = !empty($request->tax_id) ? implode(',', $request->tax_id) : '';
            $productService->unit_id        = $request->unit_id;
            $productService->product_type           = $request->product_type;
            $productService->hsn_code           = $request->hsn_code;
            $productService->low_stock_notification = $request->low_stock_notification;
//            if(isset($request->pro_image))
//            {
//                $productService->pro_image = $fileNameToStore;
//            }

            if(!empty($request->pro_image))
            {

                if($productService->pro_image)
                {
                    $path = storage_path('uploads/pro_image' . $productService->pro_image);
                    if(file_exists($path))
                    {
                        \File::delete($path);
                    }
                }
                $fileName = time() . "_" . $request->pro_image->getClientOriginalName();
                $productService->pro_image = $fileName;
                $dir        = 'uploads/pro_image';
                $path = Utility::upload_file($request,'pro_image',$fileName,$dir,[]);
            }

            $productService->created_by     = \Auth::user()->creatorId();
            $productService->save();
            CustomField::saveData($productService, $request->customField);

            return redirect()->route('productservice.index')->with('success', __('Product successfully created.'));
    }


    public function edit($id)
    {
        $productService = ProductService::find($id);

        //echo '<pre>'; print_r($productService); die();

        if($productService->created_by == \Auth::user()->creatorId())
        {
            $unit     = ProductServiceUnit::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $tax      = Tax::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');

            $productService->customField = CustomField::getData($productService, 'product');
            $customFields                = CustomField::where('created_by', '=', \Auth::user()->creatorId())->where('module', '=', 'product')->get();
            $productService->tax_id      = explode(',', $productService->tax_id);
            return view('productservice.edit', compact('unit', 'tax', 'productService', 'customFields'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }


    public function update(Request $request, $id)
    {
        $productService = ProductService::find($id);
        if($productService->created_by == \Auth::user()->creatorId())
        {

            $rules = [
                'name' => 'required',
                'sku' => 'required', Rule::unique('product_services')->ignore($productService->id),
                'purchase_price' => 'required|numeric',
                'unit_id' => 'required',
                'product_type' => 'required',
                'hsn_code' => 'required',
                'low_stock_notification' => 'required|numeric',

            ];

            $validator = \Validator::make($request->all(), $rules);

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('productservice.index')->with('error', $messages->first());
            }

            $productService->name           = $request->name;
            $productService->description    = $request->description;
            $productService->sku            = $request->sku;
            $productService->purchase_price = $request->purchase_price;
            $productService->tax_id         = !empty($request->tax_id) ? implode(',', $request->tax_id) : '';
            $productService->unit_id        = $request->unit_id;
            $productService->product_type           = $request->product_type;
            $productService->hsn_code           = $request->hsn_code;
            $productService->low_stock_notification = $request->low_stock_notification;
//                if(isset($request->pro_image))
//                {
//                    $productService->pro_image = $fileNameToStore;
//                }
            if(!empty($request->pro_image))
            {

                if($productService->pro_image)
                {
                    $path = storage_path('uploads/pro_image' . $productService->pro_image);
                    if(file_exists($path))
                    {
                        \File::delete($path);
                    }
                }
                $fileName = time() . "_" . $request->pro_image->getClientOriginalName();
                $productService->pro_image = $fileName;
                $dir        = 'uploads/pro_image';
                $path = Utility::upload_file($request,'pro_image',$fileName,$dir,[]);

            }

            $productService->created_by     = \Auth::user()->creatorId();
            $productService->save();
            CustomField::saveData($productService, $request->customField);

            return redirect()->route('productservice.index')->with('success', __('Product successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function destroy($id)
    {
        $productService = ProductService::find($id);
        if($productService->created_by == \Auth::user()->creatorId())
        {
            $productService->delete();

            return redirect()->route('productservice.index')->with('success', __('Product successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function export()
    {
        $name = 'product_service_' . date('Y-m-d i:h:s');
        $data = Excel::download(new ProductServiceExport(), $name . '.xlsx');

        return $data;
    }

    public function importFile()
    {
        return view('productservice.import');
    }

    public function import(Request $request)
    {
        $rules = [
            'file' => 'required',
        ];

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $products     = (new ProductServiceImport)->toArray(request()->file('file'))[0];
        $totalProduct = count($products) - 1;
        $errorArray   = [];
        for ($i = 1; $i <= count($products) - 1; $i++) {
            $items  = $products[$i];

            $taxes     = explode(';', $items[5]);

            $taxesData = [];
            foreach ($taxes as $tax)
            {
                $taxes       = Tax::where('id', $tax)->first();
                //                $taxesData[] = $taxes->id;
                $taxesData[] = !empty($taxes->id) ? $taxes->id : 0;


            }

            $taxData = implode(',', $taxesData);
            //            dd($taxData);

            if (!empty($productBySku)) {
                $productService = $productBySku;
            } else {
                $productService = new ProductService();
            }

            $productService->name           = $items[0];
            $productService->sale_price     = $items[1];
            $productService->purchase_price = $items[2];
            $productService->quantity       = $items[3];
            $productService->tax_id         = $items[4];
            $productService->category_id    = $items[5];
            $productService->unit_id        = $items[6];
            $productService->type           = $items[7];
            $productService->description    = $items[8];
            $productService->created_by     = \Auth::user()->creatorId();

            if (empty($productService)) {
                $errorArray[] = $productService;
            } else {
                $productService->save();
            }
        }

        $errorRecord = [];
        if (empty($errorArray)) {

            $data['status'] = 'success';
            $data['msg']    = __('Record successfully imported');
        } else {
            $data['status'] = 'error';
            $data['msg']    = count($errorArray) . ' ' . __('Record imported fail out of' . ' ' . $totalProduct . ' ' . 'record');


            foreach ($errorArray as $errorData) {

                $errorRecord[] = implode(',', $errorData);
            }

            \Session::put('errorArray', $errorRecord);
        }

        return redirect()->back()->with($data['status'], $data['msg']);
    }

    public function warehouseDetail($id)
    {
        $products = WarehouseProduct::where('product_id', '=', $id)->where('created_by', '=', \Auth::user()->creatorId())->get();
        return view('productservice.detail', compact('products'));
    }

    public function searchProducts(Request $request)
    {
//        dd($request->all());

        $lastsegment = $request->session_key;


                $output = "";
            if($request->war_id == '0'){
                $ids = WarehouseProduct::where('warehouse_id',1)->get()->pluck('product_id')->toArray();
                $productKit = ProductKit::get();
                if ($request->cat_id !== '' && $request->search == '') {
                    if($request->cat_id == '0'){
                        $products = ProductService::getallproducts()->whereIn('product_services.id',$ids)->get();
                    }else{
                        $products = ProductService::getallproducts()->where('category_id', $request->cat_id)->whereIn('product_services.id',$ids)->get();
                    }

                } else {
                    if($request->cat_id == '0'){
                        $products = ProductService::getallproducts()->where('product_services.name', 'LIKE', "%{$request->search}%")->get();
                    }else{
                        $products = ProductService::getallproducts()->where('product_services.name', 'LIKE', "%{$request->search}%")->orWhere('category_id', $request->cat_id)->get();
                    }
                }
            }else{
                $ids = WarehouseProduct::where('warehouse_id',$request->war_id)->get()->pluck('product_id')->toArray();

                $products = ProductService::getallproducts()->whereIn('product_services.id',$ids)->get();

            }


            if (count($products)>0)
            {
                foreach ($products as $key => $product)
                {
                    $usedQty = PosProduct::selectRaw('SUM(`quantity`) `qty`')
                    ->groupBy('product_id')
                    ->where('product_id',$product->id)
                    ->where('approve_status',1)
                    ->first();
                    $productPrice = ProductPriceList::where('id',$product->id)->first();
                    $mainQty = $product->quantity;
                    $quantity= intval($mainQty) - ($usedQty != null ? intval($usedQty['qty']) : 0 );

                    $unit=(!empty($product) && !empty($product->unit()))?$product->unit()->name:'';

                    if(!empty($product->pro_image)){
                        $image_url =('uploads/pro_image').'/'.$product->pro_image;
                    }else{
                        $image_url =('uploads/pro_image').'/default.png';
                    }
                    if ($request->session_key == 'purchases')
                    {
                        $productprice = $product->purchase_price != 0 ? $product->purchase_price : 0;
                    }
                    else if ($request->session_key == 'pos')
                    {
                        $productprice = $product->sale_price != 0 ? $product->sale_price : 0;
                    }
                    else
                    {
                        $productprice = $product->sale_price != 0 ? $product->sale_price : $product->purchase_price;
//                        dd($productprice);
                    }
                    if(\Auth::user()->client_type == 1){
                        $productprice = $product->alliance;
                    }elseif (\Auth::user()->client_type == 2) {
                        $productprice = $product->premium;
                    }elseif (\Auth::user()->client_type == 3) {
                        $productprice = $product->standard;
                    }

                    $output .= '

                            <div class="col-lg-2 col-md-2 col-sm-3 col-xs-4 col-12">
                                <div class="tab-pane fade show active toacart w-100" data-url="' . url('add-to-cart/' . $product->id . '/' . $lastsegment) .'">
                                    <div class="position-relative card">
                                        <img alt="Image placeholder" src="' . asset(Storage::url($image_url)) . '" class="card-image avatar shadow hover-shadow-lg" style=" height: 6rem; width: 100%;">
                                        <div class="p-0 custom-card-body card-body d-flex ">
                                            <div class="card-body my-2 p-2 text-left card-bottom-content">
                                                <h6 class="mb-2 text-dark product-title-name">' . $product->name . '</h6>
                                                <small class="badge badge-primary mb-2">' . Auth::user()->priceFormat($productprice) . '</small>
                                                <small class="mb-2 text-dark product-msp"> MSP : ' . $product->msp_margin . '</small>
                                                <small class="text-dark product-lsp"> LSP : ' . $product->lsp_margin . '</small>
                                                <small class="top-badge badge badge-danger mb-0">'. $quantity.' '.$unit .'</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                    ';

                }

                return Response($output);
            } else {
                $output='<div class="card card-body col-12 text-center">
                    <h5>'.__("No Product Available").'</h5>
                    </div>';
                return Response($output);
            }
    }

    public function addToCart(Request $request, $id,$session_key)
    {

            $product = ProductService::find($id);
            $productPrice = ProductPriceList::where('product_id',$product->id)->first();
            $productquantity = 0;

            if ($product) {

                $productquantity = $product->getTotalProductQuantity();

            }

            if (!$product || ($session_key == 'pos' && $productquantity == 0)) {
                return response()->json(
                    [
                        'code' => 404,
                        'status' => 'Error',
                        'error' => __('This product is out of stock!'),
                    ],
                    404
                );
            }

            $productname = $product->name;

            if ($session_key == 'purchases') {

                $productprice = $product->purchase_price != 0 ? $product->purchase_price : 0;
            } else if ($session_key == 'pos') {

                $productprice = $product->sale_price != 0 ? $product->sale_price : 0;
            } else {

                $productprice = $product->sale_price != 0 ? $product->sale_price : $product->purchase_price;
            }
            if(\Auth::user()->client_type == 1){
                $productprice = $productPrice->alliance;
            }elseif (\Auth::user()->client_type == 2) {
                $productprice = $productPrice->premium;
            }elseif (\Auth::user()->client_type == 3) {
                $productprice = $productPrice->standard;
            }
            $originalquantity = (int)$productquantity;

            $tax = ProductService::where('product_services.id', $id)->leftJoin(
                'taxes',
                function ($join) {
                    $join->on('taxes.id', '=', 'product_services.tax_id')
                        ->where('taxes.created_by', '=', Auth::user()->creatorId())
                        ->orWhereNull('product_services.tax_id');
                }
            )->select(DB::Raw('IFNULL( `taxes`.`rate` , 0 ) as rate'))->first();

            $producttax = $tax->rate;

            $tax = ($productprice * $producttax) / 100;
            $priceTotal = (int)$productprice * 1;

            if($request->customerLimit == 'advance_payment'){
                $user = Auth::user();
                $clientDiscount = UserClientDiscount::where('id', $user->client_type)->first();
                $treadDiscount = $clientDiscount['tread_discount'] ;
    
                $treadAmt = $priceTotal * (int)$treadDiscount / 100;
                
                $tempPrice = $priceTotal - $treadAmt;
                $addtax = $tempPrice * (int)$producttax / 100;
                $subtotal = $tempPrice + $addtax;
            }else{
                $treadDiscount = 0;
                $subtotal   = $productprice + $tax;
            }
            $cart            = session()->get($session_key);
//            $image_url       = (!empty($product->image) && Storage::exists($product->image)) ? $product->image : 'logo/placeholder.png';
            $image_url = (!empty($product->pro_image) && Storage::exists($product->pro_image)) ? $product->pro_image : 'uploads/pro_image/'. $product->pro_image;
            $treadAmt = $priceTotal * (int)$treadDiscount / 100;

            $model_delete_id = 'delete-form-' . $id;

            $carthtml = '';

            $carthtml .= '<tr data-product-id="' . $id . '" id="product-id-' . $id . '">
                            <td class="cart-images">
                                <img alt="Image placeholder" src="' . asset(Storage::url($image_url)) . '" class="card-image avatar shadow hover-shadow-lg">
                            </td>

                            <td class="name">' . $productname . '</td>

                            <td class="">
                                   <span class="quantity buttons_added">
                                         <input type="button" value="-" class="minus">
                                         <input type="number" step="1" min="1" max="" name="quantity" title="' . __('Quantity') . '" class="input-number" size="4" data-url="' . url('update-cart/') . '" data-id="' . $id . '">
                                         <input type="button" value="+" class="plus">
                                   </span>
                            </td>

                            <td class="price">' . Auth::user()->priceFormat($productprice) . '</td>

                            <td class="priceTotal">' . Auth::user()->priceFormat($priceTotal) . '</td>
                            
                            <input type="hidden" name="treadDisApply" value="'. ($priceTotal * (int)$treadDiscount / 100) .'" class="treadDisApply"/>

                            <td class="tax">' . $producttax . '% </td>

                            <td class="subtotal">' . Auth::user()->priceFormat($subtotal) . '</td>

                            <td class="">
                                 <a href="#" class="action-btn bg-danger bs-pass-para-pos" data-confirm="' . __("Are You Sure?") . '" data-text="' . __("This action can not be undone. Do you want to continue?") . '" data-confirm-yes=' . $model_delete_id . ' title="' . __('Delete') . '}" data-id="' . $id . '" title="' . __('Delete') . '"   >
                                   <span class=""><i class="ti ti-trash btn btn-sm text-white"></i></span>
                                 </a>
                                 <form method="post" action="' . url('remove-from-cart') . '"  accept-charset="UTF-8" id="' . $model_delete_id . '">
                                      <input name="_method" type="hidden" value="DELETE">
                                      <input name="_token" type="hidden" value="' . csrf_token() . '">
                                      <input type="hidden" name="session_key" value="' . $session_key . '">
                                      <input type="hidden" name="id" value="' . $id . '">
                                 </form>

                            </td>
                        </td>';



            // if cart is empty then this the first product
            if (!$cart) {
                $cart = [
                    $id => [
                        "name" => $productname,
                        "quantity" => 1,
                        "price" => $productprice,
                        "priceTotal" => $priceTotal,
                        "discount" => $treadAmt,
                        "id" => $id,
                        "tax" => $producttax,
                        "subtotal" => $subtotal,
                        "originalquantity" => $originalquantity,
                    ],
                ];


                if ($originalquantity < $cart[$id]['quantity'] && $session_key == 'pos') {
                    return response()->json(
                        [
                            'code' => 404,
                            'status' => 'Error',
                            'error' => __('This product is out of stock!'),
                        ],
                        404
                    );
                }

                session()->put($session_key, $cart);

                return response()->json(
                    [
                        'code' => 200,
                        'status' => 'Success',
                        'success' => $productname . __(' added to cart successfully!'),
                        'product' => $cart[$id],
                        'carthtml' => $carthtml,
                    ]
                );
            }

            // if cart not empty then check if this product exist then increment quantity
            if (isset($cart[$id])) {

                $cart[$id]['quantity']++;
                $cart[$id]['id'] = $id;

                $user = Auth::user();
                $clientDiscount = UserClientDiscount::where('id', $user->client_type)->first();
                $treadDiscount = $clientDiscount['tread_discount'] ;

                $subtotal = $cart[$id]["price"] * $cart[$id]["quantity"];
                $priceTotal = $cart[$id]["price"] * $cart[$id]["quantity"];
                $treadAmt = $priceTotal * (int)$treadDiscount / 100;
                $tempPrice = $priceTotal - $treadAmt;
                $addtax = $tempPrice * ((int)$cart[$id]["tax"]) / 100;
                $subtotal = $tempPrice + $addtax;
                // $tax      = ($subtotal * $cart[$id]["tax"]) / 100;

                // $cart[$id]["subtotal"]         = $subtotal + $tax;
                $cart[$id]["subtotal"] = $subtotal;
                $cart[$id]["originalquantity"] = $originalquantity;

                if ($originalquantity < $cart[$id]['quantity'] && $session_key == 'pos') {
                    return response()->json(
                        [
                            'code' => 404,
                            'status' => 'Error',
                            'error' => __('This product is out of stock!'),
                        ],
                        404
                    );
                }

                session()->put($session_key, $cart);

                return response()->json(
                    [
                        'code' => 200,
                        'status' => 'Success',
                        'success' => $productname . __(' added to cart successfully!'),
                        'product' => $cart[$id],
                        'carttotal' => $cart,
                    ]
                );
            }

            // if item not exist in cart then add to cart with quantity = 1
            $cart[$id] = [
                "name" => $productname,
                "quantity" => 1,
                "price" => $productprice,
                'priceTotal' => $priceTotal,
                "discount" => $treadAmt,
                "tax" => $producttax,
                "subtotal" => $subtotal,
                "id" => $id,
                "originalquantity" => $originalquantity,
            ];

            if ($originalquantity < $cart[$id]['quantity'] && $session_key == 'pos') {
                return response()->json(
                    [
                        'code' => 404,
                        'status' => 'Error',
                        'error' => __('This product is out of stock!'),
                    ],
                    404
                );
            }

            session()->put($session_key, $cart);

            return response()->json(
                [
                    'code' => 200,
                    'status' => 'Success',
                    'success' => $productname . __(' added to cart successfully!'),
                    'product' => $cart[$id],
                    'carthtml' => $carthtml,
                    'carttotal' => $cart,
                ]
            );
    }

    public function updateCart(Request $request)
    {


        $id          = $request->id;
        $quantity    = $request->quantity;
        $discount    = 0;
        $session_key = $request->session_key;


            $cart = session()->get($session_key);



            if (isset($cart[$id]) && $quantity == 0) {
                unset($cart[$id]);
            }

            if ($quantity) {

                $cart[$id]["quantity"] = $quantity;
                $producttax            = isset($cart[$id]["tax"]) ? $cart[$id]["tax"] : 0;
                $productprice          = isset($cart[$id]["tax"]) ? $cart[$id]["price"] : 0;
                $priceTotal = $cart[$id]["price"] * $cart[$id]["quantity"];

                if($request->customerLimit == 'advance_payment'){
                    $user = Auth::user();
                    $clientDiscount = UserClientDiscount::where('id', $user->client_type)->first();
                    $treadDiscount = $clientDiscount['tread_discount'] ;
        
                    $treadAmt = $priceTotal * (int)$treadDiscount / 100;
                    
                    $tempPrice = $priceTotal - $treadAmt;
                    $addtax = $tempPrice * (int)$producttax / 100;
                    $subtotal = $tempPrice + $addtax;
                    $cart[$id]["discount"] = $treadAmt;
                    $cart[$id]["subtotal"] = $subtotal;
                }else{
                    $treadDiscount = 0;
                    $subtotal = $productprice * $quantity;
                    $tax      = ($subtotal * $producttax) / 100;
                    $cart[$id]["discount"] = $treadDiscount;
                    $cart[$id]["subtotal"] = $subtotal + $tax;
                }

            }
            
            if (isset($cart[$id]["originalquantity"]) && $cart[$id]["originalquantity"] < $cart[$id]['quantity'] && $session_key == 'pos') {
                return response()->json(
                    [
                        'code' => 404,
                        'status' => 'Error',
                        'error' => __('This product is out of stock!'),
                    ],
                    404
                );
            }

            $subtotal = array_sum(array_column($cart, 'subtotal'));
            // $discount = $request->discount;
            // $total = $subtotal - $discount;
            // $totalDiscount = User::priceFormats($subtotal);
            // $discount = 0;

            session()->put($session_key, $cart);

            return response()->json(
                [
                    'code' => 200,
                    'success' => __('Cart updated successfully!'),
                    'product' => $cart,
                    // 'discount' => $discount,
                ]
            );
    }

    public function emptyCart(Request $request)
    {
        $session_key = $request->session_key;

        $cart = session()->get($session_key);
        if (isset($cart) && count($cart) > 0)
        {
            session()->forget($session_key);
        }

        return redirect()->back()->with('error', __('Cart is empty!'));
        
    }

    public function warehouseemptyCart(Request $request)
    {
        $session_key = $request->session_key;

            $cart = session()->get($session_key);
            if (isset($cart) && count($cart) > 0)
            {
                session()->forget($session_key);
            }

        return response()->json();

    }

    public function removeFromCart(Request $request)
    {
        $id          = $request->id;
        $session_key = $request->session_key;
        
        $cart = session()->get($session_key);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put($session_key, $cart);
        }

        return redirect()->back()->with('error', __('Product removed from cart!'));
    }

    public function costMargin($id)
    {
        $productService = ProductService::find($id);

        return view('productservice.costmargin', compact('productService'));
    }

    public function updateCostMargin(Request $request, $id)
    {
        $productService = ProductService::find($id);
        if($productService->created_by == \Auth::user()->creatorId())
        {

            $rules = [
                'import_cost' => 'required',
                'msp_margin' => 'required',
                'lsp_margin' => 'required',
            ];

            $validator = \Validator::make($request->all(), $rules);

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('productservice.index')->with('error', $messages->first());
            }

            $productService->import_cost = $request->import_cost;
            $productService->msp_margin = $request->msp_margin;
            $productService->lsp_margin = $request->lsp_margin;
           
            $productService->save();

            return redirect()->route('productservice.index')->with('success', __('Product cost successfully updated.'));
        }
    }

    public function productPrice()
    {
        $productPrices = ProductService::where('created_by', '=', \Auth::user()->creatorId())->get();
        $discountMaster = UserClientDiscount::select('client_type','discount')->get();
        // ProductPriceList
        $priceList = ProductPriceList::truncate();
        foreach ($productPrices as $key => $value) {
            $addPriceList = new ProductPriceList();
            $addPriceList->product_id   = $value['id'];
            $addPriceList->name = $value['name'];
            $addPriceList->sku  = $value['sku'];
            $addPriceList->description  = $value['description'];
            $addPriceList->purchase_price   = $value['purchase_price'];
            $addPriceList->import_cost  = $value['purchase_price'] + ($value['purchase_price'] * $value['import_cost'] / 100);
            $addPriceList->msp_margin   =  ($value['purchase_price'] + ($value['purchase_price'] * $value['import_cost'] / 100)) + $value['purchase_price'] + ($value['purchase_price'] * $value['msp_margin'] / 100);
            $addPriceList->alliance = ($value['purchase_price'] * $value['import_cost'] / 100) + ($value['purchase_price'] + ($value['purchase_price'] *  $discountMaster[0]['discount'] / 100));
            $addPriceList->premium  = ($value['purchase_price'] * $value['import_cost'] / 100) + ($value['purchase_price'] + ($value['purchase_price'] *  $discountMaster[1]['discount'] / 100));
            $addPriceList->standard = ($value['purchase_price'] * $value['import_cost'] / 100) + ($value['purchase_price'] + ($value['purchase_price'] *  $discountMaster[2]['discount'] / 100));
            $addPriceList->lsp_margin   = 
            ($value['purchase_price'] * $value['import_cost'] / 100) + ($value['purchase_price'] + ($value['purchase_price'] *  $discountMaster[2]['discount'] / 100)) + 
            ((($value['purchase_price'] * $value['import_cost'] / 100) + ($value['purchase_price'] + ($value['purchase_price'] *  $discountMaster[2]['discount'] / 100))) * $value['lsp_margin'] / 100);
            $addPriceList->import_cost_per = $value['import_cost'];
            $addPriceList->msp_margin_per = $value['msp_margin'];
            $addPriceList->lsp_margin_per = $value['lsp_margin'];
            $addPriceList->alliance_per = $discountMaster[0]['discount'];
            $addPriceList->premium_per = $discountMaster[1]['discount'];
            $addPriceList->standard_per = $discountMaster[2]['discount'];
            $addPriceList->save();
        }

        $sellingPriceList = ProductPriceList::get();
        return view('productservice.productPriceList', compact('sellingPriceList'));
    }

    public function availableProduct()
    {
        $filterOptions  = [
            "qty_list" => "Quantity List",
            "barcode_list" => "Barcode List",
        ];

        return view('productservice.filterProduct', compact('filterOptions'));
    }
    
    public function filterData(Request $request)
    {
        $settings = Utility::settings();
        $created_by = \Auth::user();
        $data  = DB::table('settings');
        $data  = $data->where('created_by', '=', $created_by->id);
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

        $color      = '#' . $settings['purchase_color'];
        $font_color = Utility::getFontColor($color);

        if($request->filter_option == 'qty_list'){
            $product = ProductService::where('quantity',0)->where('created_by', '=', \Auth::user()->creatorId())->get();
            
            $productDetails = ProductService::
            select('product_services.*','pos_products.quantity as s_qty','purchase_products.quantity as p_qty')
            ->join('pos_products', 'pos_products.product_id','=','product_services.id')
            ->join('purchase_products', 'purchase_products.product_id','=','product_services.id')
            ->where('product_services.created_by', '=', \Auth::user()->creatorId())
            ->get();
            $productDetail = array_merge($product->toArray(),$productDetails->toArray());
            // $productDetail = (object)$finalArr;
            
            return view('productservice.product_available',compact('productDetail','created_by','color', 'settings', 'img', 'font_color'));
        }else{
            $productDetail = ProductService::where('created_by', '=', \Auth::user()->creatorId())->get();
            $product_id = [];
            foreach ($productDetail as $key => $value) {
                array_push($product_id,$value['id']);
            }
            $purchaseBarcode = PurchaseBarcode::select('product_id','barcode')->whereIn('product_id',$product_id)->where('status',0)->get();
            
            return view('productservice.product_available_barcode',compact('productDetail','purchaseBarcode','created_by','color', 'settings', 'img', 'font_color'));
        }
    }

    public function stocktrack(Request $request)
    {
        $productServices = [];
        if($request->barcode != null && $request->model_no != null){
            $barcode_id = PurchaseBarcode::select('product_id')->where('barcode',$request->barcode)->first();
            if($barcode_id != null){
                $productServices = ProductService::select('*')->where('id', '=', $barcode_id['product_id'])->where('sku', '=', $request->model_no)->get();
            }
        }elseif($request->barcode != null && $request->model_no == null){
            $barcode_id = PurchaseBarcode::select('product_id')->where('barcode',$request->barcode)->first();
            if($barcode_id != null){
                $productServices = ProductService::select('*')->where('id', '=', $barcode_id['product_id'])->get();
            }
        }elseif($request->barcode == null && $request->model_no != null){
            $productServices = ProductService::select('*')->where('sku', '=', $request->model_no)->get();
        }else{
            $productServices = ProductService::select('*')->where('created_by', '=', \Auth::user()->creatorId())->get();
        }
        $productId = [];
        foreach ($productServices as $key => $value) {
            array_push($productId, $value['id']);
        }

        $sales = ProductService::
        select('sales.product_id as sales')
        ->join('pos_products as sales', 'sales.product_id','=','product_services.id')
        ->whereIn('product_id',$productId)
        ->get();

        $salesReturn = ProductService::
        select('sales_return.pos_product_id as sales_return')
        ->join('pos_returns as sales_return', 'sales_return.pos_product_id','=','product_services.id')
        ->whereIn('sales_return.pos_product_id',$productId)
        ->get();

        $purchase =ProductService::
        select('purchase.product_id as purchase')
        ->join('purchase_products as purchase', 'purchase.product_id','=','product_services.id')
        ->whereIn('purchase.product_id',$productId)
        ->get();
        
        $purchaseReturn =ProductService::
        select('purchase_return.purchase_product_id as purchase_return')
        ->join('purchase_returns as purchase_return', 'purchase_return.purchase_product_id','=','product_services.id')
        ->whereIn('purchase_return.purchase_product_id',$productId)
        ->get();

        $array = array_merge($sales->toArray(), $salesReturn->toArray(), $purchase->toArray(), $purchaseReturn->toArray());
        
        $trackingData = array_reduce($array, 'array_merge_recursive', array());

        foreach ($productServices as $key => $value) {
           foreach ($trackingData as $key1 => $value1) {
                if(is_array($value1)){
                    foreach ($value1 as $innerKey => $innerValue) {
                        if($value['id'] == $innerValue){
                            $productServices[$key][$key1] = $innerValue ;
                        }
                    }
                }else{
                    if($value['id'] == $value1){
                        $productServices[$key][$key1] = $value1 ;
                    }
                }
           }
        }
        return view('productstock.stockTracking', compact('productServices'));
    }
}