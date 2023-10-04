@extends('layouts.admin')
@section('page-title')
    {{__('Manage Product Price List')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Product Price List')}}</li>
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
                                <th>{{__('Product Code')}}</th>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Description')}}</th>
                                <th>{{__('MSP')}}</th>
                                <th>{{__('LSP')}}</th>
                                <th>{{__('ALLIANCE')}}</th>
                                <th>{{__('PREMIUM')}}</th>
                                <th>{{__('STANDARD')}}</th>
                            </tr>
                            </thead>
                            <tbody id="updateData">
                            @foreach ($sellingPriceList as $key => $productPrice)
                                <tr class="font-style">
                                    <td>{{ $productPrice->sku}}</td>
                                    <td>{{ $productPrice->name}}</td>
                                    <td>{{ Illuminate\Support\Str::limit($productPrice->description, 15) }}</td>
                                    <td>{{ $productPrice->msp_margin }}</td>
                                    <td>{{ $productPrice->lsp_margin }}</td>
                                    <td>{{ $productPrice->alliance}}</td>
                                    <td>{{ $productPrice->premium}}</td>
                                    <td>{{ $productPrice->standard}}</td>
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
