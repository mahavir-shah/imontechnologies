<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="invoice">
                <div class="invoice-print">
                    <div class="row">
                        <div class="col">
                            <small>
                                <strong>{{__('Sale Id')}} :</strong><br>
                                {{ Auth::user()->posNumberFormat($pos->pos_id) }}<br>
                            </small>
                        </div>
                        @if(!empty($vendor->billing_name))
                            <div class="col">
                                <small class="font-style">
                                    <strong>{{__('Billed To')}} :</strong><br>
                                    {{!empty($vendor->billing_name)?$vendor->billing_name:''}}<br>
                                </small>
                            </div>
                        @endif
                        @if(App\Models\Utility::getValByName('shipping_display')=='on')
                            <div class="col">
                                <small>
                                    <strong>{{__('Shipped To')}} :</strong><br>
                                    {{!empty($vendor->shipping_name)?$vendor->shipping_name:''}}<br>
                                </small>
                            </div>
                        @endif
                        <div class="col">
                            <small>
                                <strong>{{__('Issue Date')}} :</strong><br>
                                {{\Auth::user()->dateFormat($pos->created_at)}}<br>
                            </small>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="font-bold mb-2">{{__('Product Summary')}}</div>
                            <div class="table-responsive mt-3">
                                <table class="table ">
                                    <tr>
                                        <th class="text-dark" data-width="40">#</th>
                                        <th class="text-dark">{{__('Product')}}</th>
                                        <th class="text-dark">{{__('Quantity')}}</th>
                                        <th class="text-dark">{{__('Rate')}}</th>
                                        {{-- <th class="text-dark">{{__('Discount')}}</th> --}}
                                        <th class="text-dark" width="12%">{{__('Price')}}</th>
                                    </tr>
                                    @foreach($iteams as $key =>$iteam)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{!empty($iteam->product())?$iteam->product()->name:''}}</td>
                                            <td>{{$iteam->quantity}}</td>
                                            <td>{{\Auth::user()->priceFormat($iteam->price)}}</td>
                                            {{-- <td>{{\Auth::user()->priceFormat($iteam->discount)}}</td> --}}
                                            <td class="text-end">{{\Auth::user()->priceFormat(($iteam->price*$iteam->quantity))}}</td>
                                        </tr>
                                    @endforeach
                                    <tfoot>
                                    <tr>
                                        <td colspan="3"></td>
                                        <td class="text-end"><b>{{__('Discount')}}</b></td>
                                        <td class="text-end">{{\Auth::user()->priceFormat($posPayment->discount)}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"></td>
                                        <td class="text-end"><b>{{__('Total')}}</b></td>
                                        <td class="text-end">{{\Auth::user()->priceFormat($pos->getDue())}}</td>
                                    </tr>
                                    </tfoot>
                                </table>
                                <div class="float-right mb-3">
                                    <a href="{{ route('pos.sales_approve',$pos->pos_id) }}" class="text-end btn btn-success btn-done-payment rounded me-2">
                                        {{__('Accept Order')}}
                                    </a>
                                    <a href="{{ route('pos.sales_cancel',$pos->pos_id) }}" class="text-end btn btn-danger btn-done-payment rounded">
                                        {{__('Cancel Order')}}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>