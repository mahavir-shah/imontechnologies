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
<?php echo e(Form::model($productService, array('route' => array('productservice.updateCostMargin', $productService->id), 'method' => 'PUT'))); ?>

<div class="modal-body">
    <div class="row">
        <div class="form-group  col-md-6">
            <?php echo e(Form::label('import_cost', __('Import Cost (%)'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
            <?php echo e(Form::text('import_cost',null, array('class' => 'form-control allow_decimal','required'=>'required'))); ?>

        </div>
        <div class="form-group  col-md-6">
            <?php echo e(Form::label('msp_margin', __('MSP Margin (%)'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
            <?php echo e(Form::text('msp_margin',null, array('class' => 'form-control allow_decimal','required'=>'required'))); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('lsp_margin', __('LSP Margin (%)'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
            <?php echo e(Form::text('lsp_margin',null, array('class' => 'form-control allow_decimal','required'=>'required'))); ?>

        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Update')); ?>" class="btn  btn-primary">
</div>
<?php echo e(Form::close()); ?>


<?php /**PATH /home/vsgpintl/admin.imontechnologies.in/resources/views/productservice/costmargin.blade.php ENDPATH**/ ?>