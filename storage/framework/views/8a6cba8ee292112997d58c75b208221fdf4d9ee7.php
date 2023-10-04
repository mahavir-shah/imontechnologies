<?php echo e(Form::open(array('url' => 'productkit'))); ?>

<div class="modal-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('kit_name', __('Name'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
                <div class="form-icon-user">
                    <?php echo e(Form::text('kit_name', '', array('class' => 'form-control','required'=>'required'))); ?>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('kit_price', __('Price'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
                <div class="form-icon-user">
                    <?php echo e(Form::text('kit_price', '', array('class' => 'form-control','required'=>'required'))); ?>

                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <?php echo e(Form::label('product_id', __('Products'),['class'=>'form-label'])); ?>

                <?php echo e(Form::select('product_id[]', $products,null, array('class' => 'form-control select2','multiple'=>'','id'=>'choices-multiple3','required'=>'required'))); ?>

            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Create')); ?>" class="btn  btn-primary">
</div>
<?php echo e(Form::close()); ?>


<?php /**PATH /home/vsgpintl/admin.imontechnologies.in/resources/views/productkit/create.blade.php ENDPATH**/ ?>