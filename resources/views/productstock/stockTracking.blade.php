@extends('layouts.admin')
@section('page-title')
    {{__('Stock Tracking Report')}}
@endsection
@push('css-page')
    <style>
        .trackTable td{
            border: 1px solid #c6c6ca57;
        }
    </style>
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Stock Tracking')}}</li>
@endsection
@section('action-btn')
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class=" mt-2 {{isset($_GET['stocktrack'])?'show':''}}" id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(['route' => ['stocktrack.index'], 'method' => 'GET', 'id' => 'stocktrack']) }}
                        <div class="d-flex align-items-center">
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 me-2">
                                <div class="btn-box">
                                    {{ Form::label('model_no', __('Model No'),['class'=>'form-label']) }}
                                    {{ Form::text('model_no', null, ['class' => 'form-control', 'required' => 'required']) }}
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                <div class="btn-box">
                                    {{ Form::label('barcode', __('Barcode'),['class'=>'form-label']) }}
                                    {{ Form::text('barcode', null, ['class' => 'form-control','required' => 'required']) }}
                                </div>
                            </div>
                            <div class="col-auto float-end ms-2 mt-4">
                                <a href="#" class="btn btn-sm btn-primary"
                                   onclick="document.getElementById('stocktrack').submit(); return false;"
                                   data-bs-toggle="tooltip" title="{{ __('apply') }}">
                                    <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                </a>
                                <a href="{{ route('stocktrack.index') }}" class="btn btn-sm btn-danger" data-bs-toggle="tooltip"
                                   title="{{ __('Reset') }}">
                                    <span class="btn-inner--icon"><i class="ti ti-trash-off "></i></span>
                                </a>
                            </div>

                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable trackTable">
                            <thead>
                            <tr>
                                <th>{{__('Transaction Date')}}</th>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Model No')}}</th>
                                <th>{{__('Purchase')}}</th>
                                <th>{{__('Purchase Return')}}</th>
                                <th>{{__('Sales')}}</th>
                                <th>{{__('Sales Return')}}</th>
                                <th>{{__('Replace In')}}</th>
                                <th>{{__('Replace Out')}}</th>
                                <th>{{__('Repair In')}}</th>
                                <th>{{__('Repair Out')}}</th>
                                <th>{{__('Repaired In')}}</th>
                                <th>{{__('Repaired Out')}}</th>
                                <th>{{__('Return to Client')}}</th>
                                <th>{{__('Executive')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($productServices as $productService)
                                <tr class="font-style">
                                    <td>{{\Auth::user()->dateFormat($productService->created_at)}}</td>
                                    <td>{{ $productService->name }}</td>
                                    <td>{{ $productService->sku }}</td>
                                    <td>{{ $productService->purchase ? 1 : '' }}</td>
                                    <td>{{ $productService->purchase_return ? 1 : ''}}</td>
                                    <td>{{ $productService->sales ? 1 : ''}}</td>
                                    <td>{{ $productService->sales_return ? 1 : ''}}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
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
