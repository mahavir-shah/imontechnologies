@extends('layouts.admin')
@section('page-title')
    {{__('Manage Terms Condition')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Terms Condition')}}</li>
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
                                <th>{{__('Customer Type')}}</th>
                                <th>{{__('Terms Condition')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($termscondition as $termscon)
                                <tr class="font-style">
                                    <td>{{ $termscon->customer_type}}</td>
                                    <td width="60px">{{ $termscon->content}}</td>
									<td class="Action">
									   <div class="action-btn bg-info ms-2">
											<a href="{{ route('termscondition.edit',$termscon->id)}}" class="mx-3 btn btn-sm  align-items-center" title="{{__('Edit')}}"  data-title="{{__('Terms Condition Edit')}}">
												<i class="ti ti-pencil text-white"></i>
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
