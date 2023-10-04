@extends('layouts.admin')
@section('page-title')
    {{__('Manage Product Kit/Combo')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Product Kit/Combo')}}</li>
@endsection

@section('action-btn')
    <div class="float-end">
        <a href="{{ route('productkit.create') }}" data-size="lg" data-url=""  data-bs-toggle="tooltip" title="#" class="btn btn-sm btn-primary">
            <i class="ti ti-plus"></i>
        </a>

    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Alliance Selling Price')}}</th>
                                <th>{{__('Premium Selling Price')}}</th>
                                <th>{{__('Standard Selling Price')}}</th>
                                <th>{{__('Cost Price')}}</th>
                               <th>{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($productKits as $productKit)
                                <tr class="font-style">
                                    <td>{{ $productKit->kit_name}}</td>
                                    <td>{{ number_format($productKit->total_alliance_purchase_price)}}</td>
                                    <td>{{ number_format($productKit->total_premium_purchase_price)}}</td>
                                    <td>{{ number_format($productKit->total_standard_purchase_price)}}</td>
                                    <td>{{ number_format($productKit->total_cost_price) }}</td>
                                    @if(Gate::check('edit product & service') || Gate::check('delete product & service'))
                                        <td class="Action">
                                            <div class="action-btn bg-warning ms-2">
                                                <a href="{{ route('productkit.show',$productKit->id) }}" class="mx-3 btn btn-sm align-items-center" title="{{__('Prodcut Kit Details')}}" data-title="{{__('Prodcut Kit Details')}}">
                                                    <i class="ti ti-eye text-white"></i>
                                                </a>
                                            </div>

                                            @can('edit product & service')
                                                <div class="action-btn bg-info ms-2">
                                                    <!--a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="{{ route('productkit.edit',$productKit->id) }}" data-ajax-popup="true"  data-size="lg " data-bs-toggle="tooltip" title="{{__('Edit')}}"  data-title="{{__('Product Edit')}}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a-->
													<a href="{{ route('productkit.edit',$productKit->id) }}" class="mx-3 btn btn-sm  align-items-center" title="{{__('Edit')}}"  data-title="{{__('Product Edit')}}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
                                            @endcan
                                            @can('delete product & service')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['productkit.destroy', $productKit->id],'id'=>'delete-form-'.$productKit->id]) !!}
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}" ><i class="ti ti-trash text-white"></i></a>
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
