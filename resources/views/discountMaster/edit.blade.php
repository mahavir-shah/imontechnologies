<script>
    $(".allow_decimal").on("input", function(evt) {
        var self = $(this);
        self.val(self.val().replace(/[^0-9\.]/g, ''));
        if ((evt.which != 46 || self.val().indexOf('.') != -1) && (evt.which < 48 || evt.which > 57)) 
        {
            evt.preventDefault();
        }
    });
</script>
{{ Form::model($client, array('route' => array('discount-master.update', $client->id), 'method' => 'PUT')) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group">
            {{ Form::label('client_type', __('Client Type'),['class'=>'form-label']) }}
            {{ Form::text('client_type', null, array('class' => 'form-control','placeholder'=>__('Enter Client Type'),'required'=>'required','disabled')) }}
        </div>
        <div class="form-group">
            {{ Form::label('discount', __('Discount'),['class'=>'form-label']) }}
            {{ Form::text('discount', null, array('class' => 'form-control allow_decimal','placeholder'=>__('Enter Discount'),'required'=>'required')) }}
        </div>
        <div class="form-group">
            {{ Form::label('payment', __('Payment Days'),['class'=>'form-label']) }}
            {{ Form::number('payment', null, array('class' => 'form-control','placeholder'=>__('Enter Payment Days'),'required'=>'required')) }}
        </div>
        <div class="form-group">
            {{ Form::label('transaction_limit', __('Transaction Limit'),['class'=>'form-label']) }}
            {{ Form::number('transaction_limit', null, array('class' => 'form-control','placeholder'=>__('Enter Transaction Limit'),'required'=>'required')) }}
        </div>
        <div class="form-group">
            {{ Form::label('tread_discount', __('Tread Discount'),['class'=>'form-label']) }}
            {{ Form::text('tread_discount', null, array('class' => 'form-control allow_decimal','placeholder'=>__('Enter Tread Discount'),'required'=>'required')) }}
        </div>
    </div>
</div>

<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn  btn-primary">
</div>

{{Form::close()}}


