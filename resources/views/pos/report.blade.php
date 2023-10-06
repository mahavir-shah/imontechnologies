@extends('layouts.admin')
@section('page-title')
    {{__('Order')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Order')}}</li>
@endsection
@push('css-page')
    <link rel="stylesheet" href="{{ asset('css/datatable/buttons.dataTables.min.css') }}">
    <style>
        .float-right{
            float: right !important;
        }
        .text-bold{
            font-weight: 900 !important;
        }
        .text-opacity{
            opacity: 0.6;
        }
    </style>
@endpush

@push('script-page')
    <script type="text/javascript" src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>
    <script>

        var filename = $('#filename').val();

        function saveAsPDF() {
            var element = document.getElementById('printableArea');
            var opt = {
                margin: 0.3,
                filename: filename,
                image: {type: 'jpeg', quality: 1},
                html2canvas: {scale: 4, dpi: 72, letterRendering: true},
                jsPDF: {unit: 'in', format: 'A2'}
            };
            html2pdf().set(opt).from(element).toPdf().get('pdf').then(function (pdf) {
                window.open(pdf.output('bloburl'), '_blank');
            });
        }
    </script>

@endpush

@section('action-btn')
    <div class="float-end">
        <a href="#" class="btn btn-sm btn-primary" onclick="saveAsPDF()"data-bs-toggle="tooltip" title="{{__('Download')}}" data-original-title="{{__('Download')}}">
            <span class="btn-inner--icon"><i class="ti ti-download"></i></span>
        </a>
    </div>


@endsection


@section('content')
    <div id="printableArea">

    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>{{__('ORDER ID')}}</th>
                                <th>{{ __('Customer') }}</th>
                                <th>{{ __('Sales Date') }}</th>
                                <th>{{ __('Due Date') }}</th>
                                <th>{{ __('Amount') }}</th>
                                @if (Auth::user()->type == 'company')
                                <th>{{ __('Delivery Status') }}</th>
                                <th>{{ __('Status') }}</th>
                                @endif
                                @if (Auth::user()->type == 'customer')
                                <th> {{__('Status')}}</th>
                                @endif
                                <th> {{__('Action')}}</th>
                            </tr>
                            </thead>

                            <tbody>

                            @forelse ($posPayments as $key => $posPayment)
                                <tr>
                                    <td>{{ Auth::user()->posNumberFormat($posPayment->pos_id) }}</td>
                                    @if($posPayment->customer_id == 0)
                                        <td class="">{{__('Walk-in Customer')}}</td>
                                    @else
                                        <td>{{ !empty($posPayment->customer) ? $posPayment->customer->name : '' }} </td>
                                    @endif
                                    <td>{{ Auth::user()->dateFormat($posPayment->created_at)}}</td>
                                    <td>{{ $posPayment->pos_date != '0000-00-00' &&  $posPayment->pos_date != null ? Auth::user()->dateFormat($posPayment->pos_date) : '-'}}</td>
                                    <td>{{!empty($posPayment->posPayment)? \Auth::user()->priceFormat($posPayment->posPayment->discount_amount) :0}}</td>
                                    @if (Auth::user()->type == 'company')
                                        <td>
                                            @if (isset($deliveryStatus[$key]) && $deliveryStatus[$key]['status'] == 'pending')
                                                <span class="purchase_status badge p-2 px-3 rounded bg-secondary"> {{ ucfirst($deliveryStatus[$key]['status']) }} </span>
                                            @elseif (isset($deliveryStatus[$key]) && $deliveryStatus[$key]['status'] == 'picking')
                                                <span class="purchase_status badge p-2 px-3 rounded bg-warning"> {{ ucfirst($deliveryStatus[$key]['status']) }} </span>
                                            @elseif (isset($deliveryStatus[$key]) && $deliveryStatus[$key]['status'] == 'packing')
                                                <span class="purchase_status badge p-2 px-3 rounded bg-info"> {{ ucfirst($deliveryStatus[$key]['status']) }} </span>
                                            @elseif (isset($deliveryStatus[$key]) && $deliveryStatus[$key]['status'] == 'shipped')
                                                <span class="purchase_status badge p-2 px-3 rounded bg-success"> {{ ucfirst($deliveryStatus[$key]['status']) }} </span> 
                                            @else
                                                <span class="purchase_status badge p-2 px-3 rounded bg-gray-500"> {{ 'Not Added' }} </span>   
                                            @endif
                                        </td>
                                        <td>
                                            @if ($posPayment->status == 1)
                                                <span class="purchase_status badge p-2 px-3 rounded bg-warning"> {{ __('Approved')}} </span>
                                            @elseif($posPayment->status == 2)
                                                <span class="purchase_status badge p-2 px-3 rounded bg-danger"> {{ __('Cancel')}} </span>
                                            @else 
                                                <span class="purchase_status badge p-2 px-3 rounded bg-secondary"> {{ __('Not Approved')}} </span>
                                            @endif
                                        </td>
                                    @endif
                                    @if (Auth::user()->type == 'customer')
                                    <td>
                                        @if ($posPayment->status == 1)
                                            <span class="purchase_status badge p-2 px-3 rounded bg-warning"> {{ __('Approved')}} </span>
                                        @elseif($posPayment->status == 2)
                                            <span class="purchase_status badge p-2 px-3 rounded bg-danger"> {{ __('Cancel')}} </span>
                                        @else 
                                            <span class="purchase_status badge p-2 px-3 rounded bg-secondary"> {{ __('Not Approved')}} </span>
                                        @endif
                                    </td>
                                    @endif
                                    <td>
                                        @if (Auth::user()->type == 'company')
                                            @if ($posPayment->status == 0)
                                                @if ($posPayment->customer_limit == 'advance_payment' && !in_array($posPayment->id,$invoiceStatus))
                                                    <div class="action-btn bg-warning ms-2">
                                                        <button class="mx-3 btn btn-sm align-items-center disabled" title="{{__('Approve')}}" data-bs-toggle="tooltip" data-original-title="{{__('Approve')}}" data-ajax-popup="true" data-size="lg"
                                                        data-align="centered" data-url="{{route('pos.show_approve_detail',\Crypt::encrypt($posPayment->id))}}" data-title="{{__('Order Detail')}}" disabled="disabled"><i class="ti ti-check text-white text-opacity"></i></button>
                                                    </div>
                                                @elseif ($posPayment->customer_limit == 'credit_line' || ($posPayment->customer_limit == 'advance_payment' && in_array($posPayment->id,$invoiceStatus)))
                                                    <div class="action-btn bg-warning ms-2">
                                                        <button class="mx-3 btn btn-sm align-items-center" title="{{__('Approve')}}" data-bs-toggle="tooltip" data-original-title="{{__('Approve')}}" data-ajax-popup="true" data-size="lg"
                                                        data-align="centered" data-url="{{route('pos.show_approve_detail',\Crypt::encrypt($posPayment->id))}}" data-title="{{__('Order Detail')}}"><i class="ti ti-check text-bold text-white"></i></button>
                                                    </div>
                                                @endif
                                                <div class="action-btn bg-primary ms-2">
                                                    <a class="mx-3 btn btn-sm align-items-center" title="{{__('Edit Order')}}" data-bs-toggle="tooltip" data-original-title="{{__('Edit Order')}}" href="{{route('pos.sales_edit',\Crypt::encrypt($posPayment->id))}}" data-title="{{__('Edit Detail')}}"><i class="ti ti-pencil text-white"></i></a>
                                                </div>
                                                @if (!in_array($posPayment->id,$invoiceStatus))
                                                    <div class="action-btn bg-pink-400 ms-2">
                                                        <a href="#" data-url="{{ route('pos.payment',$posPayment->id) }}" data-ajax-popup="true" title="{{__('Convert into Invoice')}}" class="mx-3 btn btn-sm align-items-center" data-title="{{__('Add Payment')}}"><i class="ti ti-report-money mr-2 text-white"></i></a>
                                                    </div>
                                                @endif
                                            @endif
                                            @if ($posPayment->status == 1)
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['pos.sales_cancel', $posPayment->id],'class'=>'delete-form-btn','id'=>'delete-form-'.$posPayment->id]) !!}
                                                    <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Cancel Order')}}" data-original-title="{{__('Cancel Order')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$posPayment->id}}').submit();">
                                                        <i class="ti ti-trash text-white"></i>
                                                    </a>
                                                    {!! Form::close() !!}
                                                </div>
                                                @if (!in_array($posPayment->id,$returnCompleted))
                                                    <div class="action-btn bg-purple-400 ms-2">
                                                        <a href="{{ route('pos.pos-return',$posPayment->id) }}" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{__('Sales Return')}}" data-original-title="{{__('Sales Return')}}">
                                                            <i class="ti ti-package text-white"></i>
                                                        </a>
                                                    </div>
                                                @endif
                                                @if (in_array($posPayment->id,$returnRecordsId))
                                                    <div class="action-btn bg-gray-500 ms-2">
                                                        <a href="{{ route('pos.return-records',$posPayment->id) }}" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{__('Return records')}}" data-original-title="{{__('Return records')}}">
                                                            <i class="ti ti-files text-white"></i>
                                                        </a>
                                                    </div>
                                                @endif
                                            @endif
                                            @if (!in_array($posPayment->id,$shippingStatus) && $posPayment->status == 1)
                                                <div class="action-btn bg-gray-500 ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm bs-pass-para-pos align-items-center" data-confirm="{{ __('Are You Sure?') }}" data-text="{{__('This data will be go with pending pick list. Do you want to continue?')}}" data-confirm-yes="pending-pick" title="{{ __('Pick') }}" data-id="{{ $posPayment->id }}">
                                                        <i class="ti ti-archive text-white"></i>
                                                    </a>
                                                    {!! Form::open(['method' => 'post', 'url' => ['pickpack/pending_pick'], 'id' => 'pending-pick']) !!}
                                                    <input type="hidden" name="id" value="{{ $posPayment->id }}">
                                                    <input type="hidden" name="totalAmt" value="{{ !empty($posPayment->posPayment) ? $posPayment->posPayment->discount_amount : 0 }}">
                                                    {!! Form::close() !!}
                                                </div>
                                            @endif
                                        @endif
                                        @if (Auth::user()->type == 'customer')
                                            @if ($posPayment->status == 0)
                                                <div class="action-btn bg-danger ms-2">
                                                    <a class="mx-3 btn btn-sm align-items-center" title="{{__('Cancel Order')}}" data-bs-toggle="tooltip" data-original-title="{{__('Cancel Order')}}" href="{{route('pos.sales_cancel',\Crypt::encrypt($posPayment->id))}}" data-title="{{__('Order Detail')}}"><i class="ti ti-trash text-white"></i></a>
                                                </div>
                                            @endif
                                        @endif
                                        <div class="action-btn bg-info ms-2">
                                            <a href="{{ route('pos.get_invoice',\Crypt::encrypt($posPayment->id)) }}" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{__('Invoice')}}" data-original-title="{{__('Invoice_Detail')}}">
                                                <i class="ti ti-eye text-white"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-dark"><p>{{__('No Data Found')}}</p></td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
