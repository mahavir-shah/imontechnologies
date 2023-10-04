<?php

namespace App\Http\Controllers;

use App\Models\ClientDeal;
use App\Models\ClientPermission;
use App\Models\Contract;
use App\Models\CustomField;
use App\Models\Estimation;
use App\Models\Invoice;
use App\Models\Plan;
use App\Models\User;
use App\Models\UserClientDiscount;
use App\Models\Utility;
use http\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

class DiscountMasterController extends Controller
{
    public function __construct()
    {
        $this->middleware(
            [
                'auth',
                'XSS',
            ]
        );
    }

    public function index()
    {
        if(\Auth::user()->can('manage client'))
        {
            $user    = \Auth::user();
            $discountMaster = UserClientDiscount::get();

            return view('discountMaster.index', compact('discountMaster'));
        }
        else
        {

            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function create(Request $request)
    {
        return view('discountMaster.create');
    
    }

    public function store(Request $request)
    {
        if(\Auth::user()->can('create client'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                    // 'client_type' => 'required|unique:user_client_discounts',
                                    'discount' => 'required|max:6',
                                    'payment' => 'required|max:4',
                                    'transaction_limit' => 'required|max:10',
                                    'tread_discount' => 'required|max:6'
                                ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                if($request->ajax)
                {
                    return response()->json(['error' => $messages->first()], 401);
                }
                else
                {
                    return redirect()->back()->with('error', $messages->first());
                }
            }

            $client = new UserClientDiscount();
            $client->client_type = $request->client_type;
            $client->discount = $request->discount;
            $client->payment = $request->payment;
            $client->transaction_limit = $request->transaction_limit;
            $client->tread_discount = $request->tread_discount;
            $client->save();


            return redirect()->route('discount-master.index')->with('success', __('Discount successfully added.'));

        }
    }

    public function show()
    {
        
    }

    public function edit($ids)
    {
        if(\Auth::user()->can('edit client'))
        {
            $id = Crypt::decrypt($ids);
            $client = UserClientDiscount::find($id);
            return view('discountMaster.edit', compact('client'));
        }
        else
        {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    public function update(Request $request, $id)
    {
        if(\Auth::user()->can('edit client'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                    // 'client_type' => 'required',
                                    'discount' => 'required|max:6',
                                    'payment' => 'required|max:4',
                                    'transaction_limit' => 'required|max:10',
                                    'tread_discount' => 'required|max:6'
                                ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                if($request->ajax)
                {
                    return response()->json(['error' => $messages->first()], 401);
                }
                else
                {
                    return redirect()->back()->with('error', $messages->first());
                }
            }
            $client = UserClientDiscount::where('id',$id)->update([
                "discount" => $request->discount,
                "payment" => $request->payment,
                "transaction_limit" => $request->transaction_limit,
                "tread_discount" => $request->tread_discount,
            ]);
            
            return redirect()->back()->with('success', __('Discount Updated Successfully!'));
           
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function destroy()
    {
       
    }
}
