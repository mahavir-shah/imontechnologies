@extends('layouts.admin')
@section('page-title')
    {{__('Packing Detail')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('ship.pending')}}">{{__('Pick-Pack-Ship')}}</a></li>
    <li class="breadcrumb-item">{{__('Packing Detail')}}</li>
@endsection
@push('script-page')

    <script src="{{asset('js/jquery-ui.min.js')}}"></script>
    <script src="{{asset('js/jquery.repeater.min.js')}}"></script>
    <script type="text/javascript">
       
        $(document).on('click','#addCarton', function () {
            var cartonId = $('.cartonTable').find("tr").length;
            var pickStatus = $('.cartonTable tr:last').children('.pack-status').text();
            var totalQty = $('.cartonTable tr:last').children('.total_qty').text();
            $('#packItem').prop('disabled',true);
            if(totalQty == ''){
                show_toastr('error', 'Please Add product into an carton');
            }else{
                if(pickStatus != 'Packing In Progress'){
                    var html = `
                        <tr class="carton_${cartonId}">
                            <td>carton - ${cartonId} <input type="hidden" name="carton_id[]" value="${cartonId}" class="carton_id"/></td>
                            <td class="total_qty"></td>
                            <td class="pack-status">Packing In Progress</td>
                            <td><button type="submit" class="btn btn-info carton_status_link">Pack</button></td>
                        </tr>
                    `;
                    $('.cartonTable > tbody:last-child').append(html);
                    $('.qty_carton').prop('disabled',false);
                    $('.productTable > tbody > tr').not(':first').each(function(){
                        var getRemainQty = $(this).find('.remainQty').text();
                        var getQtyCarton = $(this).find('.qty_carton').text();
                        if(getRemainQty <= 0) {
                            $(this).find('.qty_carton').prop('disabled',true);
                        }
                        $(this).find('.qty_carton').val(0);
                    })
                }else{
                    show_toastr('error', 'Please Pack an carton.');
                }
            }
        });

        $(document).on('change','.qty_carton',function () {
            var qty = parseInt($(this).val());
            var remainQty = parseInt($(this).parent().prev('.remainQty').text());
            var remainOldQty = parseInt($(this).siblings('.remainOldQty').val());
            parseInt($(this).siblings('.qtyCarton').val(qty));
            if(qty < 0 ){
                show_toastr('error', 'Please add right product quntatity.');
            }else{
                if(remainQty < qty){
                    show_toastr('error', 'Quntatity is more than Remaining Quntatity.');
                }else{
                    var newQty = remainQty - qty;
                    if(qty > 0){
                        $(this).parent().prev('.remainQty').text(newQty);
                    }else{
                        $(this).parent().prev('.remainQty').text(remainOldQty);
                    }
                    var allqty = 0;
                    $('.qty_carton').each(function(){
                        allqty += ($(this).val()) != 'Nan' ? parseInt($(this).val()) : 0;
                    });
                    $('.total_qty').text(parseInt(allqty));
                    $('.total_qty').val(parseInt(allqty));
                }
            }
        });

        $(document).on('click','.carton_status_link',function(e) {
            var getParent = $(this).parent().parent().attr('class');
            var total_qty = $(`.${getParent}`).find('.total_qty').text();
            var getQty = [];
            $('.qty_carton').each(function(){
                getQty.push(parseInt($(this).val()));
            })
            event.preventDefault();
            if(!getQty.includes("") && total_qty != ''){
                var status =$(this).text();
                $(`.${getParent} .pack-status`).text('Packed');
                $.ajax({
                    url: `{{ route("ship.addPackingDetail")}}`,
                    type: 'POST',
                    header:{
                    'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'json',
                    data:$("#addPacking").serialize(),
                    cache: false,
                    success: function (data) {
                        // location.reload();
                        var getRemainQty = [];
                        $('.remainQty').each(function(){
                            getRemainQty.push(parseInt($(this).text()));
                        })
                        const isAllZero = getRemainQty.every(item => item === 0);
                        if(isAllZero){
                            $('#addCarton').prop('disabled',true);
                            $('.saveBtn').prop('disabled',false);
                        }
                        
                        $('.carton_status_link').prop('disabled',true);
                        $('.qty_carton').prop('disabled',true);
                    }
                });
            }else{
                show_toastr('error', 'Add product into carton.');
            }

            var statusArr = [];
            $('.cartonTable > tbody > tr').not(':first').each(function(){
                var status = $(this).children('.pack-status').text();
                statusArr.push(status);
            })
        });

        $(document).on('click','.cancelPacking',function(e) {
            var shipId = $('.shipId').val();
            $.ajax({
                url: `{{ route("ship.cancelPacking") }}`,
                type: 'POST',
                header:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                data : {
                    "_token": "{{ csrf_token() }}",
                    "id":shipId
                },
                cache: false,
                success: function (data) {
                    window.location.href = "{{ route('ship.pending')}}";
                }
            });
        });

        $(document).on('click','.saveBtn',function(e) {
            var shipId = $('.shipId').val();
            var carton = $('.carton_id').val();
            $.ajax({
                url: `{{ route("ship.savePacking") }}`,
                type: 'POST',
                header:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                data : {
                    "_token": "{{ csrf_token() }}",
                    "id":shipId,
                    "carton":carton
                },
                cache: false,
                success: function (data) {
                    window.location.href = "{{ route('ship.pending')}}";
                }
            });
        });
  </script>
@endpush

@section('content')
    <div class="row">
        {{ Form::model($posShip, array('route' => array('ship.addPackingDetail', $posShip->id), 'method' => 'POST','class'=>'w-100','id'=>'addPacking')) }}
        <div class="container">
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            <div class="row">
                <div class="col-12">
                    <div class="invoice">
                        <div class="invoice-print">
                            <div class="row">
                                <div class="col">
                                    <strong>{{__('Order Number')}} :</strong><br>
                                    <input type="text" class="form-control" value="{{$posShip->ship_unique}}" readonly>
                                    {{ Form::hidden('shipId',$posShip->id, array('class' => 'form-control shipId'))}}
                                </div>
                                <div class="col">
                                    <strong>{{__('Customer')}} :</strong><br>
                                    <input type="text" class="form-control" value="{{$customer->name}}" readonly>
                                </div>
                            </div>
                            <div class="row mt-5">
                                <div class="col-md-12">
                                    <div class="font-bold mb-2">{{__('Cartons')}}</div>
                                    <div class="table-responsive mt-3">
                                        <table class="table cartonTable">
                                            <tr>
                                                <th class="text-dark">{{__('Carton')}}</th>
                                                <th class="text-dark">{{__('Total Quantity')}}</th>
                                                <th class="text-dark">{{__('Packing Status')}}</th>
                                                <th class="text-dark">{{__('Action')}}</th>
                                            </tr>
                                            <tr class="carton_1">
                                                <td>{{ 'Carton - 1'}} 
                                                    <input type="hidden" name="carton_id[]" value="1" class="carton_id"/>
                                                </td>
                                                <td class="total_qty"></td>
                                                <td class="pack-status">{{ 'Packing In Progress' }}</td>
                                                <td><button type="submit" class="btn btn-info carton_status_link">Pack</button></td>
                                            </tr>
                                        </table>
                                        <div class="float-right mb-3">
                                            {{ Form::hidden('total_qty[]',null, array('class' => 'form-control total_qty'))}}
                                            <button type="button" class="text-start btn btn-primary rounded me-2" id="addCarton">
                                                {{__('Add a carton')}}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="font-bold mb-2">{{__('Product Summary')}}</div>
                                    <div class="table-responsive mt-3">
                                        <table class="table productTable">
                                            <tr>
                                                <th class="text-dark" data-width="40">#</th>
                                                <th class="text-dark">{{__('Product')}}</th>
                                                <th class="text-dark">{{__('Quantity')}}</th>
                                                <th class="text-dark">{{__('Remaining Quantity')}}</th>
                                                <th class="text-dark">{{__('Carton')}}</th>
                                            </tr>
                                            @foreach($iteams as $key =>$iteam)
                                                <tr class="product_{{$key}}">
                                                    <td>{{$key+1}}</td>
                                                    <td>{{!empty($iteam->product())?$iteam->product()->name:''}}</td>
                                                    <td>{{$iteam->quantity}}</td>
                                                    <td class="remainQty">{{$iteam->quantity}}
                                                    </td>
                                                    <td width="100px">
                                                        {{ Form::hidden('product_id[]',$iteam->product()->id, array('class' => 'form-control'))}}
                                                        {{ Form::hidden('qtyCarton[]',null, array('class' => 'form-control qtyCarton'))}}
                                                        <input type="number" name="qty_carton" class="form-control qty_carton" value="0"/>
                                                        <input type="hidden" name="remainOldQty" class="form-control remainOldQty" value="{{$iteam->quantity}}"/>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-primary saveBtn" disabled>Save</button>
            <button type="button" class="btn btn-dark cancelPacking">Cancel</button>
        </div>
        {{ Form::close() }}
    </div>
@endsection

