<?php echo e(Form::open(array('url' => 'products/filterData','method' => 'POST'))); ?>

<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-12">
            <?php echo e(Form::label('filter_option', __('Get Available Product'),['class'=>'form-label'])); ?>

            <?php echo e(Form::select('filter_option', $filterOptions,null, array('class' => 'form-control select','required'=>'required'))); ?>

        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="submit" value="<?php echo e(__('Print')); ?>" class="btn btn-primary">
</div>
<?php echo e(Form::close()); ?>


<?php /**PATH /home/hp/Documents/CNS/idea/resources/views/productservice/filterProduct.blade.php ENDPATH**/ ?>