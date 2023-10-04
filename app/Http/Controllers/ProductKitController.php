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

class ProductKitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $productKits = ProductKit::where('created_by', '=', \Auth::user()->creatorId())->get();
        return view('productkit.index', compact('productKits'));
    }


    public function create()
    {
        $products = ProductService::get()->pluck('name','id');
        $products->prepend('--', '');

        return view('productkit.create', compact('products'));
    }

    public function store(Request $request)
    {			
            $rules = [
                'kit_name' => 'required',
                'product_id' => 'required',
                'quantity' => 'required',
                'alliance_selling_price' => 'required',
                'premium_selling_price' => 'required',
                'standard_selling_price' => 'required',
                'cost_price' => 'required',
                'total_alliance_purchase_price' => 'required',
                'total_premium_purchase_price' => 'required',
                'total_standard_purchase_price' => 'required',
                'total_cost_price' => 'required',
            ];
			
            $validator = \Validator::make($request->all(), $rules);
            if($validator->fails()){
                $messages = $validator->getMessageBag();
                return redirect()->route('productkit.index')->with('error', $messages->first());
            }
			
			$data = $request->all();
			$product_info = [];
			for($i = 0; $i < count($data['product_id']); $i++){
				$product_info[] = [
					'product_id'=>$data['product_id'][$i],
					'quantity'=>$data['quantity'][$i],
					'alliance_selling_price'=>$data['alliance_selling_price'][$i],
					'premium_selling_price'=>$data['premium_selling_price'][$i],
					'standard_selling_price'=>$data['standard_selling_price'][$i],
					'cost_price'=>$data['cost_price'][$i]
				];
			}
            $ProductKit           = new ProductKit();

            $ProductKit->kit_name     = $data['kit_name'];
            $ProductKit->kit_price    = "";
            $ProductKit->product_info = json_encode($product_info);
            $ProductKit->total_alliance_purchase_price = $data['total_alliance_purchase_price'];
            $ProductKit->total_premium_purchase_price = $data['total_premium_purchase_price'];
            $ProductKit->total_standard_purchase_price = $data['total_standard_purchase_price'];
            $ProductKit->total_cost_price = $data['total_cost_price'];
            $ProductKit->created_by     = \Auth::user()->creatorId();
			$ProductKit->save();

            return redirect()->route('productkit.index')->with('success', __('Product kit successfully created.'));
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $productKit = ProductKit::find($id);
		$product_info = json_decode($productKit->product_info,true);	
		
        $product_id = [];
		for($i = 0; $i < count($product_info);$i++){
			$product_info[$i]['product_details'] = ProductService::where("id",$product_info[$i]['product_id'])->first();
		} 
        return view('productkit.show', compact('product_info','productKit'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $productKit = ProductKit::find($id);
        if($productKit->created_by == \Auth::user()->creatorId())
        {
			$product_info = json_decode($productKit->product_info,true);			
			$product_id = [];
			for($i = 0; $i < count($product_info);$i++){
				$product_info[$i]['product_details'] = ProductService::where("id",$product_info[$i]['product_id'])->first();
			} 
			
            $products = ProductService::get()->pluck('name','id');
            return view('productkit.edit', compact('products','productKit','product_info'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $ProductKit = ProductKit::find($id);
        if($ProductKit->created_by == \Auth::user()->creatorId())
        {
            $rules = [
                'kit_name' => 'required',
                'product_id' => 'required',
                'quantity' => 'required',
                'alliance_selling_price' => 'required',
                'premium_selling_price' => 'required',
                'standard_selling_price' => 'required',
                'cost_price' => 'required',
                'total_alliance_purchase_price' => 'required',
                'total_premium_purchase_price' => 'required',
                'total_standard_purchase_price' => 'required',
                'total_cost_price' => 'required',
            ];
			
            $validator = \Validator::make($request->all(), $rules);
            if($validator->fails()){
                $messages = $validator->getMessageBag();
                return redirect()->route('productkit.index')->with('error', $messages->first());
            }
			
			$data = $request->all();
			
			$product_info = [];
			for($i = 0; $i < count($data['product_id']); $i++){
				$product_info[] = [
					'product_id'=>$data['product_id'][$i],
					'quantity'=>$data['quantity'][$i],
					'alliance_selling_price'=>$data['alliance_selling_price'][$i],
					'premium_selling_price'=>$data['premium_selling_price'][$i],
					'standard_selling_price'=>$data['standard_selling_price'][$i],
					'cost_price'=>$data['cost_price'][$i]
				];
			}
            $ProductKit           =  ProductKit::find($id);
			
            $ProductKit->kit_name     = $data['kit_name'];
            $ProductKit->kit_price    = "";
            $ProductKit->product_info = json_encode($product_info);
            $ProductKit->total_alliance_purchase_price = $data['total_alliance_purchase_price'];
            $ProductKit->total_premium_purchase_price = $data['total_premium_purchase_price'];
            $ProductKit->total_standard_purchase_price = $data['total_standard_purchase_price'];
            $ProductKit->total_cost_price = $data['total_cost_price'];
            $ProductKit->created_by     = \Auth::user()->creatorId();
			$ProductKit->save();

            return redirect()->route('productkit.index')->with('success', __('Product successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $productKit = ProductKit::find($id);
        if($productKit->created_by == \Auth::user()->creatorId())
        {
            $productKit->delete();

            return redirect()->route('productkit.index')->with('success', __('Product kit successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
