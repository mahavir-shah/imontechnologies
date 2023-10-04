<?php echo e(Form::model($productService, array('route' => array('productservice.update', $productService->id), 'method' => 'PUT','enctype' => "multipart/form-data"))); ?>

<div class="modal-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('name', __('Name'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
                <div class="form-icon-user">
                    <?php echo e(Form::text('name',null, array('class' => 'form-control','required'=>'required'))); ?>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('sku', __('SKU'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
                <div class="form-icon-user">
                    <?php echo e(Form::text('sku', null, array('class' => 'form-control','required'=>'required'))); ?>

                </div>
            </div>
        </div>
        <div class="form-group  col-md-12">
            <?php echo e(Form::label('description', __('Description'),['class'=>'form-label'])); ?>

            <?php echo Form::textarea('description', null, ['class'=>'form-control','rows'=>'2']); ?>

        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('purchase_price', __('Purchase Price'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
                <div class="form-icon-user">
                    <?php echo e(Form::number('purchase_price', null, array('class' => 'form-control','required'=>'required','step'=>'0.01'))); ?>

                </div>
            </div>
        </div>

        <div class="form-group  col-md-6">
            <?php echo e(Form::label('tax_id', __('Tax'),['class'=>'form-label'])); ?>

            <?php echo e(Form::select('tax_id[]', $tax,null, array('class' => 'form-control select2','id'=>'choices-multiple'))); ?>

        </div>

        <div class="form-group  col-md-6">
            <?php echo e(Form::label('unit_id', __('Unit'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
            <?php echo e(Form::select('unit_id', $unit,null, array('class' => 'form-control select','required'=>'required'))); ?>

        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('hsn_code', __('HSN Code'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
                <div class="form-icon-user">
                    <?php echo e(Form::number('hsn_code', null, array('class' => 'form-control','required'=>'required'))); ?>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <div class="btn-box">
                    <label class="d-block form-label"><?php echo e(__('Type')); ?></label>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input" id="customRadio5" name="product_type" value="barcode" checked="checked"   <?php if($productService->product_type=='barcode'): ?> checked <?php endif; ?> onclick="hide_show(this)">
                                <label class="custom-control-label form-label" for="customRadio5"><?php echo e(__('Barcoded')); ?></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input" id="customRadio6" name="product_type" value="nonbarcode"  <?php if($productService->product_type=='nonbarcode'): ?> checked <?php endif; ?> onclick="hide_show(this)">
                                <label class="custom-control-label form-label" for="customRadio6"><?php echo e(__('Non Barcoded')); ?></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 form-group">
            <?php echo e(Form::label('pro_image',__('Product Image'),['class'=>'form-label'])); ?>

            <div class="choose-file ">
                <label for="pro_image" class="form-label">
                    <input type="file" class="form-control" name="pro_image" id="pro_image" data-filename="pro_image_create">
                    <img id="image"  class="mt-3" width="100" src="<?php if($productService->pro_image): ?><?php echo e(asset(Storage::url('uploads/pro_image/'.$productService->pro_image))); ?><?php else: ?><?php echo e(asset(Storage::url('uploads/pro_image/user-2_1654779769.jpg'))); ?><?php endif; ?>" />

                </label>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('low_stock', __('Low Stock'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
                <div class="form-icon-user">
                   
                    <input type="number" name="low_stock_notification" class="form-control" step="1" required value="<?php echo e($productService->low_stock_notification); ?>">
                </div>
            </div>
        </div>

    </div>
        <?php if(!$customFields->isEmpty()): ?>
            <div class="col-md-6">
                <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                    <?php echo $__env->make('customFields.formBuilder', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Update')); ?>" class="btn  btn-primary">
</div>
<?php echo e(Form::close()); ?>

<script>
    document.getElementById('pro_image').onchange = function () {
        var src = URL.createObjectURL(this.files[0])
        document.getElementById('image').src = src
    }
</script>

<?php /**PATH /var/www/html/imontechnologies/resources/views/productservice/edit.blade.php ENDPATH**/ ?>