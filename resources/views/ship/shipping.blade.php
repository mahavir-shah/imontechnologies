{{ Form::open(['method' => 'post', 'route' => ['ship.addShipForm']]) }}
<div class="modal-body">
    <div class="row">
        {{ Form::hidden('pos_ship_id',$posShip->id, array('class' => 'form-control')) }}
        {{ Form::hidden('pos_id',$posShip->pos_id, array('class' => 'form-control')) }}
        {{ Form::hidden('carton',$posShip->carton, array('class' => 'form-control')) }}
        {{ Form::hidden('product',$product, array('class' => 'form-control')) }}
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('order_number', __('Order Number'),['class'=>'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::text('order_number', $posShip->ship_unique, array('class' => 'form-control','required'=>'required','disabled')) }}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('customer', __('Customer'),['class'=>'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::text('customer', $customer->name, array('class' => 'form-control','required'=>'required','disabled')) }}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('selected_carton', __('Selected Carton'),['class'=>'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::text('selected_carton', $posShip->carton, array('class' => 'form-control','required'=>'required','disabled')) }}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('product', __('Product'),['class'=>'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::text('product', $product, array('class' => 'form-control','required'=>'required','disabled')) }}
                </div>
            </div>
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('delivery', __('Delivery'),['class'=>'form-label']) }}
            {{ Form::select('delivery', $delivery,null, array('class' => 'form-control select2','id'=>'deliverySelect')) }}
        </div>

        <div class="form-group col-md-6">
            {{ Form::label('carrierType', __('Carrier Type'),['class'=>'form-label']) }}
            <div class="form-icon-user">
                {{ Form::text('carrierType', null, array('class' => 'form-control','disabled','id' => 'carrierType')) }}
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group carrierNameDiv d-none">
                {{ Form::label('carrier_name', __('Carrier Name'),['class'=>'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::text('carrier_name', '', array('class' => 'form-control','disabled','id' => 'carrierName')) }}
                </div>
            </div>
        </div>
        <div class="col-md-6"></div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('tracking_number', __('Tracking Number'),['class'=>'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::text('tracking_number', '', array('class' => 'form-control','disabled','id' => 'trackingNumber')) }}
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('length', __('length'),['class'=>'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::text('length', '', array('class' => 'form-control')) }}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('width', __('Width'),['class'=>'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::number('width', '', array('class' => 'form-control')) }}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('height', __('Height'),['class'=>'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::number('height', '', array('class' => 'form-control')) }}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('weight', __('Weight'),['class'=>'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::number('weight', '', array('class' => 'form-control')) }}
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Save')}}" class="btn  btn-primary">
</div>
{{Form::close()}}

