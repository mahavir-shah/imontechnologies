<?php echo e(Form::open(['method' => 'post', 'route' => ['ship.addShipForm']])); ?>

<div class="modal-body">
    <div class="row">
        <?php echo e(Form::hidden('pos_ship_id',$posShip->id, array('class' => 'form-control'))); ?>

        <?php echo e(Form::hidden('pos_id',$posShip->pos_id, array('class' => 'form-control'))); ?>

        <?php echo e(Form::hidden('carton',$posShip->carton, array('class' => 'form-control'))); ?>

        <?php echo e(Form::hidden('product',$product, array('class' => 'form-control'))); ?>

        <div class="col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('order_number', __('Order Number'),['class'=>'form-label'])); ?>

                <div class="form-icon-user">
                    <?php echo e(Form::text('order_number', $posShip->ship_unique, array('class' => 'form-control','required'=>'required','disabled'))); ?>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('customer', __('Customer'),['class'=>'form-label'])); ?>

                <div class="form-icon-user">
                    <?php echo e(Form::text('customer', $customer->name, array('class' => 'form-control','required'=>'required','disabled'))); ?>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('selected_carton', __('Selected Carton'),['class'=>'form-label'])); ?>

                <div class="form-icon-user">
                    <?php echo e(Form::text('selected_carton', $posShip->carton, array('class' => 'form-control','required'=>'required','disabled'))); ?>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('product', __('Product'),['class'=>'form-label'])); ?>

                <div class="form-icon-user">
                    <?php echo e(Form::text('product', $product, array('class' => 'form-control','required'=>'required','disabled'))); ?>

                </div>
            </div>
        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('delivery', __('Delivery'),['class'=>'form-label'])); ?>

            <?php echo e(Form::select('delivery', $delivery,null, array('class' => 'form-control select2','id'=>'deliverySelect'))); ?>

        </div>

        <div class="form-group col-md-6">
            <?php echo e(Form::label('carrierType', __('Carrier Type'),['class'=>'form-label'])); ?>

            <div class="form-icon-user">
                <?php echo e(Form::text('carrierType',null, array('class' => 'form-control','disabled','id' => 'carrierType'))); ?>

            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group carrierNameDiv d-none">
                <?php echo e(Form::label('carrier_name', __('Carrier Name'),['class'=>'form-label'])); ?>

                <div class="form-icon-user">
                    <?php echo e(Form::text('carrier_name', '', array('class' => 'form-control','disabled','id' => 'carrierName'))); ?>

                </div>
            </div>
        </div>
        <div class="col-md-6"></div>
        <div class="col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('tracking_number', __('Tracking Number'),['class'=>'form-label'])); ?>

                <div class="form-icon-user">
                    <?php echo e(Form::text('tracking_number', '', array('class' => 'form-control','disabled','id' => 'trackingNumber'))); ?>

                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('length', __('length'),['class'=>'form-label'])); ?>

                <div class="form-icon-user">
                    <?php echo e(Form::text('length', '', array('class' => 'form-control'))); ?>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('width', __('Width'),['class'=>'form-label'])); ?>

                <div class="form-icon-user">
                    <?php echo e(Form::number('width', '', array('class' => 'form-control'))); ?>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('height', __('Height'),['class'=>'form-label'])); ?>

                <div class="form-icon-user">
                    <?php echo e(Form::number('height', '', array('class' => 'form-control'))); ?>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('weight', __('Weight'),['class'=>'form-label'])); ?>

                <div class="form-icon-user">
                    <?php echo e(Form::number('weight', '', array('class' => 'form-control'))); ?>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Save')); ?>" class="btn  btn-primary">
</div>
<?php echo e(Form::close()); ?>


<?php /**PATH /home/vsgpintl/admin.imontechnologies.in/resources/views/ship/shipping.blade.php ENDPATH**/ ?>