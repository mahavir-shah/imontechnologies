<form class="" method="post" action="{{ route('pos.findBarcode') }}" >
    @csrf

    <div class="modal-body">
        <div class="row">
            <div class="form-group col-md-12">
                {{ Form::label('Serial Number', __('Serial Number'), ['class' => 'form-label text-dark']) }}
                {{ Form::text('serial_no',null, array('class' => 'form-control','required'=>'required','placeholder'=>__('Serial no'),'required'=>'required')) }}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="submit" value="{{__('Find Barcode')}}" class="btn btn-primary">
    </div>
</form>