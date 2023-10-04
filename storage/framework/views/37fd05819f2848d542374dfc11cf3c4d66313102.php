<form class="" method="post" action="<?php echo e(route('pos.findBarcode')); ?>" >
    <?php echo csrf_field(); ?>

    <div class="modal-body">
        <div class="row">
            <div class="form-group col-md-12">
                <?php echo e(Form::label('Serial Number', __('Serial Number'), ['class' => 'form-label text-dark'])); ?>

                <?php echo e(Form::text('serial_no',null, array('class' => 'form-control','required'=>'required','placeholder'=>__('Serial no'),'required'=>'required'))); ?>

            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="submit" value="<?php echo e(__('Find Barcode')); ?>" class="btn btn-primary">
    </div>
</form><?php /**PATH /home/vsgpintl/admin.imontechnologies.in/resources/views/pos/get_barcode_print.blade.php ENDPATH**/ ?>