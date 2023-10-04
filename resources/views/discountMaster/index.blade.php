@extends('layouts.admin')
@php
   // $profile=asset(Storage::url('uploads/avatar/'));
    $profile=\App\Models\Utility::get_file('uploads/avatar/');
@endphp
@section('page-title')
    {{__('Manage Discount Master')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Discount Master')}}</li>
@endsection
{{-- @section('action-btn')
    <div class="float-end">
        <a href="#" data-size="md" data-url="{{ route('discount-master.create') }}" data-ajax-popup="true"  data-bs-toggle="tooltip" title="{{__('Create')}}"  class="btn btn-sm btn-primary">
            <i class="ti ti-plus"></i>
        </a>
    </div>
@endsection --}}
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th> {{__('Id')}}</th>
                                <th> {{__('Client Type')}}</th>
                                <th> {{__('Discount')}}</th>
                                <th> {{__('Payment Days')}}</th>
                                <th>{{__('Transaction Limit')}}</th>
                                <th>{{__('Tread Discount')}}</th>
                                <th> {{__('Action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($discountMaster as $client)
                                <tr>
                                    <td>{{$client->id }}</td>
                                    <td> {{$client->client_type }} </td>
                                    <td> {{$client->discount . '%' }} </td>
                                    <td> {{$client->payment}} </td>
                                    <td> {{$client->transaction_limit}} </td>
                                    <td> {{$client->tread_discount . '%'}} </td>
                                    <td class="Action">
                                        <span>
                                            <div class="action-btn bg-primary ms-2">
                                                <a href="#!" data-size="md" data-url="{{ route('discount-master.edit',\Crypt::encrypt($client->id)) }}" data-ajax-popup="true" class="mx-3 btn btn-sm align-items-center" data-bs-original-title="{{__('Edit')}}">
                                                    <i class="ti ti-pencil text-white"></i>
                                                </a>
                                            </div>
                                        </span>
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
