@extends('layouts.admin')
@section('page-title')
    {{__('POS Product Barcode')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('POS Product Barcode')}}</li>
@endsection
@push('css-page')
    <link rel="stylesheet" href="{{ asset('css/datatable/buttons.dataTables.min.css') }}">
@endpush

@section('action-btn')
    <div class="float-end">
        <a data-url="{{ route('pos.getBarcodePrint') }}" data-ajax-popup="true" data-bs-toggle="tooltip" data-title="{{__('Search Barcode')}}" title="{{__('Search Barcode And Print')}}" class="btn btn-sm btn-primary">
            <i class="ti ti-search text-white"></i>
        </a>
        <a data-url="{{ route('pos.setting') }}" data-ajax-popup="true" data-bs-toggle="tooltip" data-title="{{__('Barcode Setting')}}" title="{{__('Barcode Setting')}}" class="btn btn-sm btn-primary">
            <i class="ti ti-settings text-white"></i>
        </a>

    </div>
@endsection

@section('content')
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive ">
                        <table class="table datatable-barcode" >
                            <thead>
                                <tr>
                                    <th>{{__('Purchase')}}</th>
                                    <th>{{ __('Barcode') }}</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($purchase as $item)
                                    <tr>
                                        <td>{{ Auth::user()->purchaseNumberFormat($item->id) }}</td>
                                        <td>
                                            <a href="{{ route('pos.print', $item->id) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{__('Print Barcode')}}">
                                                <i class="ti ti-scan text-white"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-dark"><p>{{__('No Data Found')}}</p></td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-page')
{{--    <script src="{{ asset('public/js/jquery-barcode.min.js') }}"></script>--}}
    <script src="{{ asset('public/js/jquery-barcode.js') }}"></script>
    <script>

        setTimeout(myGreeting, 1000);
        function myGreeting() {
            if ($(".datatable-barcode").length > 0) {
                const dataTable =  new simpleDatatables.DataTable(".datatable-barcode");
            }
        }
        // });
    </script>

@endpush
