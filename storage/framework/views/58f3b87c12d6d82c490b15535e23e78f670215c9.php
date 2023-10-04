<?php echo e(Form::model($customer,array('route' => array('customer.update', $customer->id), 'method' => 'PUT'))); ?>

<div class="modal-body">

    <h6 class="sub-title"><?php echo e(__('Basic Info')); ?></h6>
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="form-group">
                <?php echo e(Form::label('name',__('Name'),array('class'=>'form-label'))); ?>

                <?php echo e(Form::text('name',null,array('class'=>'form-control','required'=>'required'))); ?>


            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="form-group">
                <?php echo e(Form::label('contact',__('Contact'),['class'=>'form-label'])); ?>

                <?php echo e(Form::text('contact',null,array('class'=>'form-control','required'=>'required'))); ?>


            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="form-group">
                <?php echo e(Form::label('email',__('Email'),['class'=>'form-label'])); ?>

                <?php echo e(Form::text('email',null,array('class'=>'form-control'))); ?>


            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="form-group">
                <?php echo e(Form::label('tax_number',__('Tax Number'),['class'=>'form-label'])); ?>

                <?php echo e(Form::text('tax_number',null,array('class'=>'form-control'))); ?>


            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="form-group">
                <?php echo e(Form::label('gst_number',__('GST Number'),['class'=>'form-label'])); ?>

                <?php echo e(Form::text('gst',null,array('class'=>'form-control'))); ?>

            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="form-group">
                <?php echo e(Form::label('pan_number',__('PAN Number'),['class'=>'form-label'])); ?>

                <?php echo e(Form::text('pan',null,array('class'=>'form-control'))); ?>

            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="form-group">
                <?php echo e(Form::label('website',__('Website'),['class'=>'form-label'])); ?>

                <?php echo e(Form::text('website',null,array('class'=>'form-control'))); ?>

            </div>
        </div>
        <?php if(!$customFields->isEmpty()): ?>
            <div class="col-lg-4 col-md-4 col-sm-6">
                <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                    <?php echo $__env->make('customFields.formBuilder', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <h6 class="sub-title"><?php echo e(__('Dealer Detail')); ?></h6>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                <?php echo e(Form::label('dealer_category',__('Dealer Category'),array('class'=>'form-label'))); ?>

                <select class="form-control select clientType" id="dealer_category" name="dealer_category">
                    <?php $__currentLoopData = $clientType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>" <?php echo e($type == $dealer_category ? 'selected' : ''); ?>><?php echo e($type); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                <?php echo e(Form::label('discount',__('Discount'),array('class'=>'','class'=>'form-label'))); ?>

                <input class="form-control discount" name="discount" type="text" value="<?php echo e($customer->discount != null ? $customer->discount : 20); ?>"/>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                <?php echo e(Form::label('credit_day',__('Credit Day'),array('class'=>'','class'=>'form-label'))); ?>

                <?php echo e(Form::text('credit_day',null,array('class'=>'form-control'))); ?>

            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('credit_limit',__('Credit Limit'),array('class'=>'form-label'))); ?>

                <?php echo e(Form::text('credit_limit',null,array('class'=>'form-control'))); ?>


            </div>
        </div>
    </div>

    <h6 class="sub-title"><?php echo e(__('Account Detail')); ?></h6>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                <?php echo e(Form::label('bank_name',__('Bank Name | Branch'),array('class'=>'form-label'))); ?>

                <?php echo e(Form::text('bank_name',null,array('class'=>'form-control'))); ?>

            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                <?php echo e(Form::label('account_no',__('Account No'),array('class'=>'','class'=>'form-label'))); ?>

                <?php echo e(Form::text('bank_account_name',null,array('class'=>'form-control'))); ?>

            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('ifsc_code',__('IFSC Code'),array('class'=>'form-label'))); ?>

                <?php echo e(Form::text('bank_ifsc_code',null,array('class'=>'form-control'))); ?>


            </div>
        </div>
    </div>

    <h6 class="sub-title"><?php echo e(__('Billing Address')); ?></h6>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                <?php echo e(Form::label('billing_name',__('Name'),array('class'=>'','class'=>'form-label'))); ?>

                <?php echo e(Form::text('billing_name',null,array('class'=>'form-control'))); ?>


            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                <?php echo e(Form::label('billing_phone',__('Phone'),array('class'=>'form-label'))); ?>

                <?php echo e(Form::text('billing_phone',null,array('class'=>'form-control'))); ?>


            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <?php echo e(Form::label('billing_address',__('Address'),array('class'=>'form-label'))); ?>

                <?php echo e(Form::textarea('billing_address',null,array('class'=>'form-control','rows'=>3))); ?>


            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                <?php echo e(Form::label('billing_city',__('City'),array('class'=>'form-label'))); ?>

                <?php echo e(Form::text('billing_city',null,array('class'=>'form-control'))); ?>


            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                <?php echo e(Form::label('billing_state',__('State'),array('class'=>'form-label'))); ?>

                <?php echo e(Form::text('billing_state',null,array('class'=>'form-control'))); ?>


            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                <?php echo e(Form::label('billing_country',__('Country'),array('class'=>'form-label'))); ?>

                <?php echo e(Form::text('billing_country',null,array('class'=>'form-control'))); ?>


            </div>
        </div>


        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                <?php echo e(Form::label('billing_zip',__('Zip Code'),array('class'=>'form-label'))); ?>

                <?php echo e(Form::text('billing_zip',null,array('class'=>'form-control'))); ?>


            </div>
        </div>

    </div>

    <?php if(App\Models\Utility::getValByName('shipping_display')=='on'): ?>
        <div class="col-md-12 text-end">
            <input type="button" id="billing_data" value="<?php echo e(__('Shipping Same As Billing')); ?>" class="btn btn-primary">
        </div>
        <h6 class="sub-title"><?php echo e(__('Shipping Address')); ?></h6>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    <?php echo e(Form::label('shipping_name',__('Name'),array('class'=>'form-label'))); ?>

                    <?php echo e(Form::text('shipping_name',null,array('class'=>'form-control'))); ?>


                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    <?php echo e(Form::label('shipping_phone',__('Phone'),array('class'=>'form-label'))); ?>

                    <?php echo e(Form::text('shipping_phone',null,array('class'=>'form-control'))); ?>


                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <?php echo e(Form::label('shipping_address',__('Address'),array('class'=>'form-label'))); ?>

                    <label class="form-label" for="example2cols1Input"></label>
                    <div class="input-group">
                        <?php echo e(Form::textarea('shipping_address',null,array('class'=>'form-control','rows'=>3))); ?>

                    </div>
                </div>
            </div>


            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    <?php echo e(Form::label('shipping_city',__('City'),array('class'=>'form-label'))); ?>

                    <?php echo e(Form::text('shipping_city',null,array('class'=>'form-control'))); ?>


                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    <?php echo e(Form::label('shipping_state',__('State'),array('class'=>'form-label'))); ?>

                    <?php echo e(Form::text('shipping_state',null,array('class'=>'form-control'))); ?>


                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    <?php echo e(Form::label('shipping_country',__('Country'),array('class'=>'form-label'))); ?>

                    <?php echo e(Form::text('shipping_country',null,array('class'=>'form-control'))); ?>


                </div>
            </div>


            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    <?php echo e(Form::label('shipping_zip',__('Zip Code'),array('class'=>'form-label'))); ?>

                    <?php echo e(Form::text('shipping_zip',null,array('class'=>'form-control'))); ?>

                </div>
            </div>

        </div>
    <?php endif; ?>

</div>
<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Update')); ?>" class="btn btn-primary">
</div>
<?php echo e(Form::close()); ?>

<?php /**PATH /home/hp/Documents/CNS/idea/resources/views/customer/edit.blade.php ENDPATH**/ ?>