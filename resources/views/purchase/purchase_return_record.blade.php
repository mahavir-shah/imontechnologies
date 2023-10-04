@extends('layouts.admin')
@section('page-title')
    {{__('Manage Purchase')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('purchase.index')}}">{{__('Purchase')}}</a></li>
    <li class="breadcrumb-item">{{__('Purchase Return Record')}}</li>
@endsection
@push('css-page')
    <style>
        .list-group-bg{
            background-color : #e9efef99;
        }
        .list-active {
            background-color : #bfc8e799 !important;
        }
        .list-group-flush:first-of-type .list-group-bg{
            background-color : #bfc8e799;
        }
        .justifyContent{
            justify-content: space-between;
        }
        .left-menu span{
            font-weight: 700;
        }
        .list-group-item p{
            color: grey;
            margin-bottom: 0 !important;
        }
    </style>
@endpush
@push('script-page')
    <script>
        function returnRecordFilter(e, id, purchase_id) {
            var goodId = id;
            var purchase_id = purchase_id;
            $.ajax({
                url: '{{route('purchase.returnFilter')}}',
                type: 'get',
                data: {
                    'good_id': goodId,
                    'purchase_id' : purchase_id
                },
                cache: false,
                success: function (data) {
                    $('.list-group-flush:first-of-type .list-group-bg').css("background-color","#e9efef99");
                    $('.list-active').removeClass('list-active');
                    $(e).addClass('list-active');
                   
                    $('.goodsRecord').empty();
                    data.goods.map(item => {
                        var html = 
                        `<tr>
                            <td>${item.name}</td>
                            <td>${item.description ? item.description : '-'}</td>
                            <td>${item.price}</td>
                            <td>${item.required_qty}</td>
                            <td>${item.return_qty}</td>
                         </tr>
                        `;
                        $('.goodsRecord').append(html);
                    });
                }
            });
        }
    </script>
@endpush    
@section('content')
    {{-- <div class="row justify-content-between align-items-center mb-3">
        <div class="col-md-12 d-flex align-items-center justify-content-between justify-content-md-end">
            <div class="all-button-box">
                <a href="{{ route('purchase.purchase_return_pdf', Crypt::encrypt($purchase->id))}}" target="_blank" class="btn btn-sm btn-primary">
                    {{__('Download')}}
                </a>
            </div>
        </div>
    </div> --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Return Date</label>
                                        <input type="text" class="form-control" value="{{ isset($getReturnRecord[0]['return_date']) ? $getReturnRecord[0]['return_date'] : ''}}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" id="vender-box">
                                        {{ Form::label('vender_id', __('Vendor'),['class'=>'form-label']) }}
                                        {{ Form::select('vender_id', $venders,null, array('class' => 'form-control select','id'=>'vender','data-url'=>route('purchase.vender'),'required'=>'required','readonly')) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('warehouse_id', __('Warehouse'),['class'=>'form-label']) }}
                                        {{ Form::select('warehouse_id', $warehouse,null, array('class' => 'form-control select','required'=>'required','readonly')) }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Purchase Number</label>
                                        <input type="text" class="form-control" value="{{$purchase_number}}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row">
                <div class="col-2 list-goods">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    @foreach ($getPurReturnId as $goods)
                        <div class="list-group list-group-flush my-2" id="useradd-sidenav">
                            <a href="#" onclick="returnRecordFilter(this,'{{$goods->return_id}}','{{$goods->purchase_id}}')" class="list-group-item list-group-item-action border-0 d-flex justifyContent list-group-bg"> 
                                <div class="left-menu">
                                    <span>{{$goods->return_id}}</span>
                                    <p>{{$goods->return_date}}</p>
                                </div>       
                            </a>
                        </div>
                    @endforeach
                </div>
                <div class="col-1">
                    @foreach ($getPurReturnId as $goods)
                        <div class="pt-4 pb-3">
                            <a href="{{ route('purchase.purchase_return_single_pdf',['unique_id' => $goods->return_id,'id' => $goods->purchase_id])}}" target="_blank" class="btn btn-primary">
                                <i class="ti ti-scan text-white"></i>
                            </a>  
                        </div>
                    @endforeach
                </div>
                <div class="col-9">
                    <div class="card">
                        <div class="card-body table-border-style">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th> {{__('Purchase')}}</th>
                                        <th> {{__('Description')}}</th>
                                        <th> {{__('Price')}}</th>
                                        <th>{{__('Required')}}</th>
                                        <th> {{__('Returned')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody class="goodsRecord">
                                        @foreach ($getReturnRecord as $goods)
                                            <tr>
                                                <td> {{ $goods->name }} </td>
                                                <td> {{ $goods->description  ? $goods->description  : '-'}} </td>
                                                <td> {{ $goods->price }} </td>
                                                <td> {{ $goods->required_qty }} </td>
                                                <td> {{ $goods->return_qty }} </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

