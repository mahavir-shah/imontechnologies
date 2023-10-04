<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Mail\SelledInvoice;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\InvoiceProduct;
use App\Models\Pos;
use App\Models\PosPayment;
use App\Models\PosProduct;
use App\Models\PosShip;
use App\Models\PosShipDetail;
use App\Models\PosPickingDetail;
use App\Models\PosPackingDetail;
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

class ShippingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pos = Pos::find($request->id);
        $ship_number  = \Auth::user()->shipNumberFormat($this->shipNumber());
        $shipOrder = new PosShip();
        $shipOrder->pos_id = $pos->id;
        $shipOrder->ship_unique = $ship_number;
        $shipOrder->customer_id = $pos->customer_id;
        $shipOrder->total_amt = $request->totalAmt;
        $shipOrder->created_by     = \Auth::user()->creatorId();
        $shipOrder->save();
        
        return redirect()->back()->with('success', 'Picking successfully added.');
    }

    public function pendingList(Request $request)
    {
        $shipList = PosShip::get();
        return view('ship.index', compact('shipList'));
    }

    public function shipCancel($id)
    {
        $shipProduct = PosShip::where('id', $id)->update(["status" => 'Cancelled']);
        
        return redirect()->back()->with('success', __('Ship product cancel successfully.'));
    }

    public function showPicking($id)
    {
        $id = Crypt::decrypt($id);
        $posShip = PosShip::find($id);
        $customer      = $posShip->customer;
        $iteams      = $posShip->items;

        return view('ship.picking', compact('posShip', 'customer', 'iteams'));
    }

    public function addPicking($id)
    {
        $posShip = PosShip::where('id',$id)->update(['status' => 'picking']);

        return redirect()->back()->with('success', __('Ship picking updated successfully.'));
    }

    public function showPacking($id)
    {
        $id = Crypt::decrypt($id);
        $posShip = PosShip::find($id);
        $customer      = $posShip->customer;
        $iteams      = $posShip->items;

        return view('ship.packing', compact('posShip', 'customer', 'iteams'));
    }

    public function addPacking(Request $request)
    {
        $posShip = PosShip::where('id',$request->id)->update([
            'carton' => $request->carton,
            'status' => 'packing'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ship picking updated successfully.'
        ], 200);
    }

    public function addPackingDetail(Request $request)
    {
        $findData = PosShip::where('id',$request->shipId)->first();
        $find = PosPackingDetail::where('ship_id',$request->shipId)->first();
        if(!empty($findData)){
            foreach ($request->product_id as $key => $value) {
                $ship_detail = new PosPackingDetail();
                $ship_detail->pos_id = $findData->pos_id;
                $ship_detail->ship_id = $request->shipId;
                $ship_detail->product_id = $value;
                $ship_detail->carton = $request['carton_id'][0];
                $ship_detail->qty = $request['qtyCarton'][$key];
                $ship_detail->created_by     = \Auth::user()->creatorId();
                $ship_detail->save();
            }
            
        }

        return response()->json(['message' => 'carton added'], 200);
    }

    public function cancelPacking(Request $request)
    {
        $packingDetail = PosPackingDetail::where('ship_id',$request->id)->delete();
        $posShip = PosShip::where('id',$request->id)->update([
            'carton' => null,
            'status' => 'picking'
        ]);
        return response()->json(['message' => 'carton cancelled'], 200);
    }

    public function savePacking(Request $request)
    {
        $posShip = PosShip::where('id',$request->id)->update([
            'carton' => $request->carton,
            'status' => 'packing'
        ]);

        return response()->json(['message' => 'carton saved'], 200);
    }
    public function checkBarcode(Request $request)
    {
        $getBarcode = PurchaseBarcode::select('product_id')->where('barcode',$request->barcode)->whereIn('product_id', $request->product_id)->where('status',0)->first();
        if(isset($getBarcode) && $getBarcode != null){
            $posShip = PurchaseBarcode::where('barcode',$request->barcode)->update([
                'status' => 1
            ]);
            return response()->json([
                'success' => true,
                'message' => $getBarcode
            ], 200);
        }else{
            return response()->json([
                'success' => true,
                'message' => 'Barcode not found or already used.'
            ], 200);
        }
    }

    public function addPickingData(Request $request, $id)
    {
        $findData = PosShip::where('id',$id)->first();
        $find = PosPickingDetail::where('ship_id',$id)->first();
        if(empty($find)){
            $requestData = $request->all();
            foreach ($request->product_id as $key => $value) {
                $ship_detail = new PosPickingDetail();
                $ship_detail->pos_id = $findData->pos_id;
                $ship_detail->ship_id = $id;
                $ship_detail->product_id = $value;
                $ship_detail->qty = $request['remainQty'][$key];
                $ship_detail->created_by     = \Auth::user()->creatorId();
                $ship_detail->save();
            }
            $posShip = PosShip::where('id',$id)->update(['status' => 'picking']);
        }
        return redirect()->route('ship.pending')->with('success', __('Picking updated successfully.'));
    }
    public function addShipForm(Request $request)
    {
        // $validator = \Validator::make(
        //     $request->all(), [
        //         'vender_id' => 'required',
        //         'warehouse_id' => 'required',
        //         'purchase_date' => 'required',
        //         'items' => 'required',
        //     ]
        // );
        // if($validator->fails())
        // {
        //     $messages = $validator->getMessageBag();

        //     return redirect()->back()->with('error', $messages->first());
        // }
        $find = PosShipDetail::where('pos_ship_id',$request->pos_ship_id)->first();
        if(empty($find)){
            $ship_detail = new PosShipDetail();
            $ship_detail->pos_id = $request->pos_id;
            $ship_detail->pos_ship_id = $request->pos_ship_id;
            $ship_detail->product_total = $request->product;
            $ship_detail->carton = $request->carton;
            $ship_detail->delivery = $request->delivery;
            $ship_detail->carrier_type = $request->carrier_type ? $request->carrier_type : '';
            $ship_detail->carrier_name = $request->carrier_name ? $request->carrier_name : '';
            $ship_detail->tracking_number = $request->tracking_number ? $request->tracking_number : 0;
            $ship_detail->length = $request->length ? $request->length : '';
            $ship_detail->width = $request->width ? $request->width : 0;
            $ship_detail->height = $request->height ? $request->height : 0;
            $ship_detail->weight = $request->weight ? $request->weight : 0;
            $ship_detail->created_by     = \Auth::user()->creatorId();
            $ship_detail->save();
        }

        $posShip = PosShip::where('id',$request->pos_ship_id)->update([
            'status' => 'shipped'
        ]);
        return redirect()->back()->with('success', __('Ship updated successfully.'));
    }

    public function showShipForm($id)
    {
        $id = Crypt::decrypt($id);
        $posShip = PosShip::find($id);
        $customer      = $posShip->customer;
        $iteams      = $posShip->items;
        $product = 0;
        foreach ($iteams as $key => $value) {
            $product += $value['quantity'];
        }
        $delivery = [
            "pickup" => "Pickup",
            "courier" => "Courier",
            "dropOff" => "DropOff",
        ];
        return view('ship.shipping', compact('posShip','customer','product','delivery'));
    }

    function shipNumber()
    {
        $latest = PosShip::latest()->first();
        if(!$latest)
        {
            return 1;
        }

        return $latest->id + 1;
    }
}

