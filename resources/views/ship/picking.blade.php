@extends('layouts.admin')
@section('page-title')
    {{__('Picking Detail')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('ship.pending')}}">{{__('Pick-Pack-Ship')}}</a></li>
    <li class="breadcrumb-item">{{__('Picking Detail')}}</li>
@endsection
@push('css-page')
    <style>
        .qtyInput{
            width: 100px;
        }
    </style>
@endpush
@push('script-page')
    <script src="{{asset('js/jquery-ui.min.js')}}"></script>
    <script>
        $(document).on('click','#addQtyByBarcode', function () {
            var barcode = $('#barcodeInput').val();
            var product_id = [];
            $('.productId').each(function() {
                var id = $(this).val();
                product_id.push(id);
            })
            if(barcode != ''){
                $.ajax({
                    url: `{{ route("ship.checkBarcode") }}`,
                    type: 'GET',
                    header:{
                        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'barcode': barcode,
                        'product_id' : product_id 
                    },
                    cache: false,
                    success: function (data) {
                        if(data.message.product_id){
                            var productFound = data.message.product_id;
                            $('.productId').each(function() {
                                var id = $(this).val();
                                var usedQty = parseInt($('.useQty_' + productFound).text());
                                if(parseInt(id) === parseInt(productFound)){
                                    var getqty = $('.qtyhandle_coded_' + productFound).val();
                                    var newQty = parseInt(getqty) + 1; 
                                    if(usedQty >= newQty){
                                        $('.qtyhandle_coded_' + productFound).val(newQty);
                                    }else{
                                        show_toastr('error', 'Quantity already picked.'); 
                                    }
                                }
                            })
                        }else{
                            show_toastr('error', 'Barcode already used or not found.'); 
                        }
                    }
                });
            }else{
                show_toastr('error', 'Please add barcode.');
            }
        })
    </script>
@endpush

@section('content')
    <div class="container">
       
        <div class="row">
            <div class="col-12">
                <div class="invoice">
                    <div class="invoice-print">
                        <div class="row mt-4">
                            <div class="col">
                                <strong>{{__('Order Number')}} </strong><br>
                                <input type="text" class="form-control" value="{{$posShip->ship_unique}}" readonly>
                            </div>
                            <div class="col">
                                <strong>{{__('Customer')}}</strong><br>
                                <input type="text" class="form-control" value="{{$customer->name}}" readonly>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="font-bold mb-2">{{__('Product quantity using barcode')}}</div>
                                <div class="row">
                                    <div class="col-4">
                                        <div class="d-flex">
                                            <input type="text" class="form-control" id="barcodeInput" placeholder="Barcode">
                                            <button class="text-end btn btn-success me-2" id="addQtyByBarcode">
                                                {{__('Add')}}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                {{ Form::model($posShip, array('route' => array('ship.addPickingData', $posShip->id), 'method' => 'POST','class'=>'w-100')) }}
                                <div class="font-bold mb-2 mt-4">{{__('Product Summary')}}</div>
                                <div class="table-responsive mt-3">
                                    <table class="table">
                                        <tr>
                                            <th class="text-dark" data-width="40">#</th>
                                            <th class="text-dark">{{__('Product')}}</th>
                                            <th class="text-dark">{{__('Quantity')}}</th>
                                            <th class="text-dark">{{__('Required Quantity')}}</th>
                                        </tr>
                                        @if(count($iteams) > 0)
                                            @foreach($iteams as $key =>$iteam)
                                                <tr>
                                                    <td>{{$key+1}}</td>
                                                    <td>
                                                        {{!empty($iteam->product())?$iteam->product()->name:''}} 
                                                        <input type="hidden" name="product_id[]" class="productId" value="{{$iteam->product()->id}}"/>
                                                    </td>
                                                    <td class="useQty_{{$iteam->product()->id}}">{{$iteam->quantity}}</td>
                                                    @if ($iteam->product()->product_type == 'barcode')
                                                        <td class="remainQty"><input class="qtyInput qtyhandle_coded_{{$iteam->product()->id}} form-control" type="number" name="remainQty[]" value="0" readonly/>
                                                        </td>
                                                    @else
                                                        <td class="remainQty"><input class="qtyInput qtyhandle_{{$iteam->product()->id}} form-control" type="number" name="remainQty[]" value="0"/>
                                                        </td>    
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="4">No Product found.</td>
                                            </tr>
                                        @endif
                                    </table>
                                    <div class="float-right mb-3">
                                        <input type="submit" value="{{__('Pick')}}" class="text-end btn btn-success rounded me-2">
                                    </div>
                                </div>
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection