@extends('layouts.admin')
@section('page-title')
    {{__('Manage Purchase')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Purchase')}}</li>
@endsection
@push('script-page')
    <script>

        $('.copy_link').click(function (e) {
            e.preventDefault();
            var copyText = $(this).attr('href');

            document.addEventListener('copy', function (e) {
                e.clipboardData.setData('text/plain', copyText);
                e.preventDefault();
            }, true);

            document.execCommand('copy');
            show_toastr('success', 'Url copied to clipboard', 'success');
        });
    </script>
@endpush


@section('action-btn')
    <div class="float-end">


{{--        <a href="{{ route('bill.export') }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{__('Export')}}">--}}
{{--            <i class="ti ti-file-export"></i>--}}
{{--        </a>--}}

        @can('create purchase')
            <a href="{{ route('purchase.create',0) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{__('Create')}}">
                <i class="ti ti-plus"></i>
            </a>
        @endcan
    </div>
@endsection


@section('content')


    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th> {{__('Purchase')}}</th>
                                <th> {{__('Vendor')}}</th>
                                <th> {{__('Due Date')}}</th>
                                <th> {{__('Purchase Date')}}</th>
                                <th>{{__('Status')}}</th>
                                <th> {{__('Goods Received')}}</th>
                                @if(Gate::check('edit purchase') || Gate::check('delete purchase') || Gate::check('show purchase'))
                                    <th > {{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>

                                {{-- {{dd($purchases)}} --}}
                            @foreach ($purchases as $purchase)

                                <tr>
                                    <td class="Id">
                                        <a href="{{ route('purchase.show',\Crypt::encrypt($purchase->id)) }}" class="btn btn-outline-primary">{{ Auth::user()->purchaseNumberFormat($purchase->id) }}</a>

                                    </td>

                                    <td> {{ (!empty( $purchase->vender)?$purchase->vender->name:'') }} </td>

                                    <td>{{ $purchase->due_date != '0000-00-00' ? Auth::user()->dateFormat($purchase->due_date) : '-'}}</td>
                                    <td>{{ Auth::user()->dateFormat($purchase->purchase_date) }}</td>

                                    <td>
                                        @if($purchase->status == 0)
                                            <span class="purchase_status badge bg-secondary p-2 px-3 rounded">{{ __(\App\Models\Purchase::$statues[$purchase->status]) }}</span>
                                        @elseif($purchase->status == 1)
                                            <span class="purchase_status badge bg-secondary p-2 px-3 rounded">{{ __(\App\Models\Purchase::$statues[$purchase->status]) }}</span>
                                        @elseif($purchase->status == 2)
                                            <span class="purchase_status badge bg-danger p-2 px-3 rounded">{{ __(\App\Models\Purchase::$statues[$purchase->status]) }}</span>
                                        @elseif($purchase->status == 3)
                                            <span class="purchase_status badge bg-info p-2 px-3 rounded">{{ __(\App\Models\Purchase::$statues[$purchase->status]) }}</span>
                                        @elseif($purchase->status == 4)
                                            <span class="purchase_status badge bg-primary p-2 px-3 rounded">{{ __(\App\Models\Purchase::$statues[$purchase->status]) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!in_array($purchase->id,$goodCompleted) && count($goodRecordsId) > 0 && !in_array($purchase->id,$goodRecordsId))
                                            <span class="purchase_status badge bg-secondary p-2 px-3 rounded">{{ 'Not Received' }}</span>
                                        @elseif(!in_array($purchase->id,$goodCompleted)  && count($goodRecordsId) > 0 && in_array($purchase->id,$goodRecordsId))
                                            <span class="purchase_status badge bg-warning p-2 px-3 rounded">{{ 'Partial' }}</span>
                                        @elseif(in_array($purchase->id,$goodCompleted) && count($goodRecordsId) > 0)
                                            <span class="purchase_status badge bg-danger p-2 px-3 rounded">{{ 'Received'}}</span>
                                        @else
                                            <span class="purchase_status badge bg-secondary p-2 px-3 rounded">{{ 'Not Applicable' }}</span>
                                        @endif
                                    </td>

                                    @if(Gate::check('edit purchase') || Gate::check('delete purchase') || Gate::check('show purchase'))
                                        <td class="Action">
                                            <span>
                                                @can('show purchase')
                                                    <div class="action-btn bg-info ms-2">
                                                            <a href="{{ route('purchase.show',\Crypt::encrypt($purchase->id)) }}" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{__('Show')}}" data-original-title="{{__('Detail')}}">
                                                                <i class="ti ti-eye text-white"></i>
                                                            </a>
                                                        </div>
                                                @endcan
                                                @can('edit purchase')
                                                    <div class="action-btn bg-primary ms-2">
                                                        <a href="{{ route('purchase.edit',\Crypt::encrypt($purchase->id)) }}" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="Edit" data-original-title="{{__('Edit')}}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                @endcan
                                                @can('delete purchase')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['purchase.destroy', $purchase->id],'class'=>'delete-form-btn','id'=>'delete-form-'.$purchase->id]) !!}
                                                        <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$purchase->id}}').submit();">
                                                            <i class="ti ti-trash text-white"></i>
                                                        </a>
                                                        {!! Form::close() !!}
                                                    </div>
                                                @endcan
                                                @if ($purchase->status == 0 ||$purchase->status == 1||$purchase->status == 3)
                                                    <div class="action-btn bg-purple-400 ms-2">
                                                        <a href="{{ route('purchase.goods-received',$purchase->id) }}" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{__('Goods received')}}" data-original-title="{{__('Goods received')}}">
                                                            <i class="ti ti-truck text-white"></i>
                                                        </a>
                                                    </div>
                                                @endif
                                                @if (count($goodRecordsId) > 0 && in_array($purchase->id,$goodRecordsId))
                                                    <div class="action-btn bg-gray-500 ms-2">
                                                        <a href="{{ route('purchase.goods-records',$purchase->id) }}" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{__('Goods records')}}" data-original-title="{{__('Goods records')}}">
                                                            <i class="ti ti-files text-white"></i>
                                                        </a>
                                                    </div>
                                                    <div class="action-btn bg-warning ms-2">
                                                        <a href="{{ route('pos.print',$purchase->id) }}" target="_blank" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{__('Barcode')}}" data-original-title="{{__('Barcode')}}">
                                                            <i class="ti ti-scan text-white"></i>
                                                        </a>
                                                    </div>
                                                @endif
                                                @if (count($goodRecordsId) > 0 && in_array($purchase->id,$goodRecordsId) && !in_array($purchase->id,$returnCompleted))
                                                    <div class="action-btn bg-purple-400 ms-2">
                                                        <a href="{{ route('purchase.purchase-return',$purchase->id) }}" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{__('Purchase Return')}}" data-original-title="{{__('Purchase Return')}}">
                                                            <i class="ti ti-package text-white"></i>
                                                        </a>
                                                    </div>
                                                @endif
                                                @if (count($returnRecordsId) > 0 && in_array($purchase->id,$returnRecordsId))
                                                    <div class="action-btn bg-gray-500 ms-2">
                                                        <a href="{{ route('purchase.return-records',$purchase->id) }}" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{__('Return records')}}" data-original-title="{{__('Return records')}}">
                                                            <i class="ti ti-files text-white"></i>
                                                        </a>
                                                    </div>
                                                @endif
                                                @if ($purchase->status == 0 ||$purchase->status == 1||$purchase->status == 3)
                                                    <div class="action-btn bg-pink-400 ms-2">
                                                        <a href="#" data-url="{{ route('purchase.payment',$purchase->id) }}" data-ajax-popup="true" title="{{__('Add Payment')}}" class="mx-3 btn btn-sm align-items-center" data-title="{{__('Add Payment')}}"><i class="ti ti-report-money mr-2 text-white"></i></a>
                                                    </div>
                                                @endif
                                            </span>
                                        </td>
                                    @endif
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

