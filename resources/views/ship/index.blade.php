@extends('layouts.admin')
@section('page-title')
    {{__('Pick-Pack-Ship')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Pick-Pack-Ship')}}</li>
@endsection
@push('css-page')
    <style>
        .carton_status_link{
            text-decoration: underline;
        }
    </style>
@endpush
@section('content')

    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th> {{__('Ship')}}</th>
                                <th> {{__('Customer')}}</th>
                                <th> {{__('Total Amount')}}</th>
                                <th> {{__('Carton')}}</th>
                                <th> {{__('Due Date')}}</th>
                                <th> {{__('Status')}}</th>
                                <th> {{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($shipList as $shipItem)

                                <tr>
                                    <td class="Id">{{$shipItem->ship_unique}}</td>
                                    <td> {{ (!empty( $shipItem->customer)?$shipItem->customer->name:'') }} </td>
                                    <td>{{!empty($shipItem->total_amt)? \Auth::user()->priceFormat($shipItem->total_amt) :0}}</td>
                                    <td>{{ $shipItem->carton != null ?  'Carton - ' . $shipItem->carton : '-'}}</td>
                                    <td>{{ $shipItem->created_at != '0000-00-00' ? Auth::user()->dateFormat($shipItem->created_at) : '-'}}</td>
                                    <td>
                                        @if ($shipItem->status == 'pending')
                                            <span class="purchase_status badge p-2 px-3 rounded bg-secondary"> {{ ucfirst($shipItem->status) }} </span>
                                        @elseif ($shipItem->status == 'picking')
                                            <span class="purchase_status badge p-2 px-3 rounded bg-warning"> {{ 'Ready to pack' }} </span>
                                        @elseif ($shipItem->status == 'packing')
                                            <span class="purchase_status badge p-2 px-3 rounded bg-info"> {{ 'Ready to ship' }} </span>
                                        @elseif ($shipItem->status == 'shipped')
                                            <span class="purchase_status badge p-2 px-3 rounded bg-success"> {{ 'Shipped' }} </span> 
                                        @else
                                            <span class="purchase_status badge p-2 px-3 rounded bg-secondary"> {{ ucfirst($shipItem->status) }} </span>   
                                        @endif
                                    </td>
                                    <td>
                                        @if ($shipItem->status == 'pending')
                                            {{-- <div class="action-btn bg-warning ms-2">
                                                <button class="mx-3 btn btn-sm align-items-center" title="{{__('Picking')}}" data-bs-toggle="tooltip" data-original-title="{{__('Picking')}}" data-ajax-popup="true" data-size="lg"
                                                data-align="centered" data-url="{{route('ship.showPicking',\Crypt::encrypt($shipItem->id))}}" data-title="{{__('Picking')}}"><i class="ti ti-shopping-cart text-white"></i></button>
                                            </div> --}}
                                            <div class="action-btn bg-warning ms-2">
                                                <a href="{{route('ship.showPicking',\Crypt::encrypt($shipItem->id))}}" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{__('Picking')}}" data-original-title="{{__('Picking')}}">
                                                    <i class="ti ti-shopping-cart text-white"></i>
                                                </a>
                                            </div>
                                        @endif
                                        @if ($shipItem->status == 'picking')
                                            {{-- <div class="action-btn bg-info ms-2">
                                                <button class="mx-3 btn btn-sm align-items-center" title="{{__('Packing')}}" data-bs-toggle="tooltip" data-original-title="{{__('Packing')}}" data-ajax-popup="true" data-size="lg"
                                                data-align="centered" data-url="{{route('ship.showPacking',\Crypt::encrypt($shipItem->id))}}" data-title="{{__('Packing')}}"><i class="ti ti-box text-white"></i></button>
                                            </div> --}}
                                            <div class="action-btn bg-info ms-2">
                                                <a href="{{route('ship.showPacking',\Crypt::encrypt($shipItem->id))}}" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{__('Packing')}}" data-original-title="{{__('Packing')}}">
                                                    <i class="ti ti-box text-white"></i>
                                                </a>
                                            </div>
                                        @endif
                                        @if ($shipItem->status == 'packing')
                                            <div class="action-btn bg-primary ms-2">
                                                <button class="mx-3 btn btn-sm align-items-center" title="{{__('Ship')}}" data-bs-toggle="tooltip" data-original-title="{{__('Ship')}}" data-ajax-popup="true" data-size="lg"
                                                data-align="centered" data-url="{{route('ship.showShipForm',\Crypt::encrypt($shipItem->id))}}" data-title="{{__('Ship')}}"><i class="ti ti-archive text-white"></i></button>
                                            </div>
                                        @endif
                                        @if($shipItem->status != 'Cancelled')
                                            <div class="action-btn bg-danger ms-2">
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['ship.cancelShip', $shipItem->id],'class'=>'delete-form-btn','id'=>'delete-form-'.$shipItem->id]) !!}
                                                <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$shipItem->id}}').submit();">
                                                    <i class="ti ti-trash text-white"></i>
                                                </a>
                                                {!! Form::close() !!}
                                            </div>
                                        @endif
                                        <div class="action-btn bg-success ms-2">
                                            <a href="{{ route('pos.ship_pdf', Crypt::encrypt($shipItem->id))}}" target="_blank" class="mx-3 btn btn-sm align-items-center"  data-bs-toggle="tooltip" title="{{__('Download')}}" data-original-title="{{__('Download')}}">
                                                <i class="ti ti-download text-white"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-page')
    <script src="{{asset('js/jquery-ui.min.js')}}"></script>
    <script type="text/javascript">
        $(document).on('submit', '#packItem', function () {
            var statusArr = [];
            console.log("form submited");
            var data = $("#shipAddForm").serialize();
            $('.cartonTable > tbody > tr').not(':first').each(function(){
                var status = $(this).children('.pack-status').text();
                statusArr.push(status);
            })
            if(statusArr.includes('Packing In Progress','Pack')){
                show_toastr('error', 'Please pack an carton');
            }else{
                var carton = 0;
                var cartonArr = $('input[name=carton]').map(function(){
                  return carton += parseInt($(this).val());
                }).get();
                var id = $('#shipId').val();
                if(carton != 0){
                  $.ajax({
                      url: `{{ route("ship.addPacking") }}`,
                      type: 'GET',
                      header:{
                        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                        },
                      data: {
                          'id': id,
                          'carton' : carton,
                          'data' : data
                      },
                      cache: false,
                      success: function (data) {
                          location.reload();
                      }
                  });
                }
            }
        });

        $(document).on('change', '#deliverySelect', function () {
            var delivery = $(this).val();
            if(delivery == 'courier'){
                $('#trackingNumber').prop("disabled", false);
                $('#carrierName').prop("disabled", false);
                $('.carrierNameDiv').removeClass('d-none');
                $('#carrierType').prop("disabled", false);
            }else{
                $('#trackingNumber').prop("disabled", true);
                $('#carrierName').prop("disabled", true);
                $('.carrierNameDiv').addClass('d-none');
                $('#carrierType').prop("disabled", true);
            }
        });

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
                            <td>carton - ${cartonId} <input type="hidden" name="carton_id" value="${cartonId}" class="carton_id"/></td>
                            <td class="total_qty"></td>
                            <td class="pack-status">Packing In Progress</td>
                            <td><a href="#" class="carton_status_link">Pack</a></td>
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
                }
            }
        })

        $(document).on('click','.carton_status_link',function() {
            var getParent = $(this).parent().parent().attr('class');
            var total_qty = $(`.${getParent}`).find('.total_qty').text();
            var getQty = [];
            $('.qty_carton').each(function(){
                getQty.push(parseInt($(this).val()));
            })
            if(!getQty.includes("") && total_qty != ''){
                var status =$(this).text();
                if(status == 'Pack'){
                    $(`.${getParent} .pack-status`).text('Packed');
                    $(`.${getParent} .carton_status_link`).text('Open');
                    $('.qty_carton').prop('disabled',true);
                    var allqty = 0;
                    $('.qty_carton').each(function(){
                        allqty += ($(this).val()) != 'Nan' ? parseInt($(this).val()) : 0;
                    });
                }else if(status == 'Open'){
                    $(`.${getParent} .pack-status`).text('Packing In Progress');
                    $(`.${getParent} .carton_status_link`).text('Pack');
                    $('.qty_carton').prop('disabled',false);
                }
            }else{
                show_toastr('error', 'Add product into carton.');
            }

            var statusArr = [];
            $('.cartonTable > tbody > tr').not(':first').each(function(){
                var status = $(this).children('.pack-status').text();
                statusArr.push(status);
            })
            if(!statusArr.includes('Packing In Progress')){
                $('#packItem').removeClass('disabled');
                $('#packItem').prop('disabled',false);
            }
        })
  </script>
@endpush
