@extends('layouts.admin')
@section('page-title')
    {{__('Manage Estimate')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Estimate')}}</li>
@endsection

@section('action-btn')
    <div class="float-end">

        <a href="{{route('proposal.export')}}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{__('Export')}}">
            <i class="ti ti-file-export"></i>
        </a>

        @can('create proposal')
            <a href="{{ route('proposal.create',0) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{__('Create')}}">
                <i class="ti ti-plus"></i>
            </a>
        @endcan
    </div>

@endsection
@push('css-page')

@endpush
@push('script-page')

@endpush
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th> {{__('Estimate')}}</th>
                                @if(!\Auth::guard('customer')->check())
                                    <th> {{__('Customer')}}</th>
                                @endif
                                <th> {{__('Category')}}</th>
                                <th> {{__('Estimate Date')}}</th>
                                <th> {{__('Status')}}</th>
                                @if(Gate::check('edit proposal') || Gate::check('delete proposal') || Gate::check('show proposal'))
                                    <th width="10%"> {{__('Action')}}</th>
                                @endif
                                {{-- <th>
                                    <td class="barcode">
                                        {!! DNS1D::getBarcodeHTML($invoice->sku, "C128",1.4,22) !!}
                                        <p class="pid">{{$invoice->sku}}</p>
                                    </td>
                                </th> --}}
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($proposals as $proposal)
                                <tr class="font-style">
                                    <td class="Id">
                                        @if(\Auth::guard('customer')->check())
                                            <a href="{{ route('customer.proposal.show',\Crypt::encrypt($proposal->id)) }}" class="btn btn-outline-primary">{{ AUth::user()->proposalNumberFormat($proposal->proposal_id) }}
                                            </a>
                                        @else
                                            <a href="{{ route('estimate.show',\Crypt::encrypt($proposal->id)) }}" class="btn btn-outline-primary">{{ AUth::user()->proposalNumberFormat($proposal->proposal_id) }}
                                            </a>
                                        @endif
                                    </td>
                                    @if(!\Auth::guard('customer')->check())
                                        <td> {{!empty($proposal->customer)? $proposal->customer->name:'' }} </td>
                                    @endif
                                    <td>{{ !empty($proposal->category)?$proposal->category->name:''}}</td>
                                    <td>{{ Auth::user()->dateFormat($proposal->issue_date) }}</td>
                                    <td>
                                        @if($proposal->status == 0)
                                            <span class="status_badge badge bg-primary p-2 px-3 rounded">{{ __(\App\Models\Proposal::$statues[$proposal->status]) }}</span>
                                        @elseif($proposal->status == 1)
                                            <span class="status_badge badge bg-info p-2 px-3 rounded">{{ __(\App\Models\Proposal::$statues[$proposal->status]) }}</span>
                                        @elseif($proposal->status == 2)
                                            <span class="status_badge badge bg-success p-2 px-3 rounded">{{ __(\App\Models\Proposal::$statues[$proposal->status]) }}</span>
                                        @elseif($proposal->status == 3)
                                            <span class="status_badge badge bg-warning p-2 px-3 rounded">{{ __(\App\Models\Proposal::$statues[$proposal->status]) }}</span>
                                        @elseif($proposal->status == 4)
                                            <span class="status_badge badge bg-danger p-2 px-3 rounded">{{ __(\App\Models\Proposal::$statues[$proposal->status]) }}</span>
                                        @endif
                                    </td>
                                    @if(Gate::check('edit proposal') || Gate::check('delete proposal') || Gate::check('show proposal'))
                                        <td class="Action">
                                            @if($proposal->is_convert==0)
                                                @can('convert invoice')
                                                    <div class="action-btn bg-warning ms-2">
                                                        {!! Form::open(['method' => 'get', 'route' => ['proposal.convert', $proposal->id],'id'=>'proposal-form-'.$proposal->id]) !!}

                                                        <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip"
                                                           title="{{__('Convert Invoice')}}" data-original-title="{{__('Convert to Invoice')}}" data-original-title="{{__('Delete')}}" data-confirm="{{__('You want to confirm convert to invoice. Press Yes to continue or Cancel to go back')}}" data-confirm-yes="document.getElementById('proposal-form-{{$proposal->id}}').submit();">
                                                            <i class="ti ti-exchange text-white"></i>
                                                            {!! Form::close() !!}
                                                        </a>
                                                    </div>
                                                @endcan
                                            @else
                                                @can('show invoice')
                                                    <div class="action-btn bg-warning ms-2">
                                                        <a href="{{ route('invoice.show',\Crypt::encrypt($proposal->converted_invoice_id)) }}"
                                                           class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip" title="{{__('Already convert to Invoice')}}" data-original-title="{{__('Already convert to Invoice')}}" >
                                                            <i class="ti ti-file text-white"></i>
                                                        </a>
                                                    </div>
                                                @endcan
                                            @endif
                                            @can('duplicate proposal')
                                                <div class="action-btn bg-success ms-2">
                                                    {!! Form::open(['method' => 'get', 'route' => ['proposal.duplicate', $proposal->id],'id'=>'duplicate-form-'.$proposal->id]) !!}

                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Duplicate')}}" data-original-title="{{__('Duplicate')}}" data-original-title="{{__('Delete')}}" data-confirm="{{__('You want to confirm duplicate this invoice. Press Yes to continue or Cancel to go back')}}" data-confirm-yes="document.getElementById('duplicate-form-{{$proposal->id}}').submit();">
                                                        <i class="ti ti-copy text-white text-white"></i>
                                                        {!! Form::close() !!}
                                                    </a>
                                                </div>
                                            @endcan
                                            @can('show proposal')
                                                @if(\Auth::guard('customer')->check())
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="{{ route('customer.proposal.show',\Crypt::encrypt($proposal->id)) }}" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{__('Show')}}" data-original-title="{{__('Detail')}}">
                                                            <i class="ti ti-eye text-white text-white"></i>
                                                        </a>
                                                    </div>
                                                @else
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="{{ route('estimate.show',\Crypt::encrypt($proposal->id)) }}" class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip" title="{{__('Show')}}" data-original-title="{{__('Detail')}}">
                                                            <i class="ti ti-eye text-white text-white"></i>
                                                        </a>
                                                    </div>
                                                @endif
                                            @endcan
                                            @can('edit proposal')
                                                <div class="action-btn bg-primary ms-2">
                                                    <a href="{{ route('estimate.edit',\Crypt::encrypt($proposal->id)) }}" class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-original-title="{{__('Edit')}}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
                                            @endcan

                                            @can('delete proposal')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['estimate.destroy', $proposal->id],'id'=>'delete-form-'.$proposal->id]) !!}

                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$proposal->id}}').submit();">
                                                        <i class="ti ti-trash text-white text-white"></i>
                                                    </a>
                                                    {!! Form::close() !!}
                                                </div>
                                            @endcan
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
