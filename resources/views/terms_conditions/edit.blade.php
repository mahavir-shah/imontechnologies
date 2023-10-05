 @extends('layouts.admin')
@section('page-title')
    {{__('Settings')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('termscondition.index')}}">{{__('Terms & Condition')}}</a></li>
    <li class="breadcrumb-item">{{__('Edit')}}</li>
@endsection

@push('css-page')
    <link rel="stylesheet" href="{{asset('css/summernote/summernote-bs4.css')}}">
@endpush

@push('script-page')
    <script src="{{asset('css/summernote/summernote-bs4.js')}}"></script>
@endpush

@section('content')
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="row card">
                <div class="col-xl-12">
                         {{ Form::open(['route' => ['termscondition.update',$termscondition->id ], 'method' => 'post']) }}
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    {{Form::label(' *',__('Customer Type:'),array('class' => 'form-label')) }}
									{{ $termscondition->customer_type }}
                                </div>
                                <div class="form-group col-12">
                                    {{Form::label('content',__(' Format'),['class'=>'form-label text-dark'])}}
                                    <textarea name="content"  class="summernote-simple2 summernote-simple">{!! isset($termscondition->content) ? $termscondition->content : "" !!}</textarea>

                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <div class="form-group">
                                <input class="btn btn-print-invoice btn-primary m-r-10" type="submit" value="{{__('Save Changes')}}">
                            </div>
                        </div>
                        {{Form::close()}}
                </div>
                <!-- [ sample-page ] end -->
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
@endsection
