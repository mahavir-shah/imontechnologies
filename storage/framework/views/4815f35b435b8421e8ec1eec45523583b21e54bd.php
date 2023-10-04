<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Purchase Create')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('purchase.index')); ?>"><?php echo e(__('Purchase')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Purchase Create')); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('script-page'); ?>
    <script src="<?php echo e(asset('js/jquery-ui.min.js')); ?>"></script>  
    <script>
    $(document).on('change', '.item', function () {
            var iteams_id = $(this).val();
            console.log(iteams_id)
            var url = $(this).data('url');
            var el = $(this);
            $.ajax({
                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': jQuery("#csrf-token").val()
                },
                data: {
                    'product_id': iteams_id , 
                    '_token' : '<?php echo e(csrf_token()); ?>'
                },
                cache: false,
                success: function (data) {
                    var item = JSON.parse(data);
					console.log

                },
            });
        });
        </script> 
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>
    <div class="row"><?php echo e(Form::open(array('url' => 'productkit'))); ?>

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
        <!-- <div class="col-md-12">
            <div class="form-group">
                <?php echo e(Form::label('product_id', __('Products'),['class'=>'form-label'])); ?>

                <?php echo e(Form::select('product_id[]', $products,null, array('class' => 'form-control select2','multiple'=>'','id'=>'choices-multiple3','required'=>'required'))); ?>

            </div>
        </div> -->
        <div class="col-md-12">
            <div class="form-group">
            <?php echo e(Form::label('product_id', __('Products'),['class'=>'form-label'])); ?>

            <table class="table">
                <thead>
                    <tr>
                    <th scope="col">Sr No</th>
                    <th scope="col">Item Details</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Selling Price</th>
                    <th scope="col">Cost Price</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    <th scope="row">1</th>
                    <td>
                    <?php echo e(Form::label('product_id', __('Products'),['class'=>'form-label'])); ?>

                    <?php echo e(Form::select('product_id', $products,null, array('class' => 'form-control select2 item','data-url'=>route('purchase.product'),'required'=>'required'))); ?>

                    
                    </td>
                    <td>
                    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" >
                    </td>
                    <td>@mdo</td>
                    <td>@mdo</td>
                    </tr>
                    <tr>
                    
                </tbody>
            </table>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('kit_name', __('Total Product Price'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
                <div class="form-icon-user">
                    <?php echo e(Form::text('total_purchase_price	', '', array('class' => 'form-control','required'=>'required'))); ?>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('kit_name', __('Total Cost Price'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
                <div class="form-icon-user">
                    <?php echo e(Form::text('total_cost_price	', '', array('class' => 'form-control','required'=>'required'))); ?>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Create')); ?>" class="btn  btn-primary">
</div>
<?php echo e(Form::close()); ?>


<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\ds-store\resources\views/productkit/create.blade.php ENDPATH**/ ?>