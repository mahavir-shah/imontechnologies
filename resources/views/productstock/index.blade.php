@extends('layouts.admin')
@section('page-title')
    {{__('Manage Product Stock')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Product Stock')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        <a href="#" data-size="md" data-url="{{ route('products.availableProduct') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Available Product Details')}}" class="btn btn-sm btn-primary">
            <i class="ti ti-scan"></i>
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
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Sku') }}</th>
                                <th>{{ __('Current Quantity') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($productServices as $productService)
                                <tr class="font-style">
                                    <td>{{ $productService->name }}</td>
                                    <td>{{ $productService->sku }}</td>
                                    <td>{{ $productService->quantity }}</td>
                                    <td class="Action text-center">
                                        <!--div class="action-btn bg-info ms-2">
                                            <a data-size="md" href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-url="{{ route('productstock.edit', $productService->id) }}" data-ajax-popup="#"  data-size="xl" data-bs-toggle="tooltip" title="{{__('Update Quantity')}}" style="position: relative;">
                                                <i class="ti ti-plus text-white"></i>
                                            </a>
                                            
                                        </div-->
										@if(isset($productService->low_stock_notification))
											@if(intval($productService->quantity) <= intval($productService->low_stock_notification))
												<button class="btn-danger align-items-center" style="border-radius: 10px;">Low Stock</button>
											@else
												-
											@endif
										@else
											-
										@endif
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
