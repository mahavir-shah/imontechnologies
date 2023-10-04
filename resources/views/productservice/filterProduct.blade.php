{{ Form::open(array('url' => 'products/filterData','method' => 'POST')) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-12">
            {{ Form::label('filter_option', __('Get Available Product'),['class'=>'form-label']) }}
            {{ Form::select('filter_option', $filterOptions,null, array('class' => 'form-control select','required'=>'required')) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="submit" value="{{__('Print')}}" class="btn btn-primary">
</div>
{{Form::close()}}

