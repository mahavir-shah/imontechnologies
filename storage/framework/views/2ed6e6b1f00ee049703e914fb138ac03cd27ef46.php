<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Picking Detail')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('ship.pending')); ?>"><?php echo e(__('Pick-Pack-Ship')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Picking Detail')); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('css-page'); ?>
    <style>
        .qtyInput{
            width: 100px;
        }
    </style>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('script-page'); ?>
    <script src="<?php echo e(asset('js/jquery-ui.min.js')); ?>"></script>
    <script>
        $(document).on('click','#addQtyByBarcode', function () {
            var barcode = $('#barcodeInput').val();
            var product_id = [];
            $('.productId').each(function() {
                var id = $(this).val();
                product_id.push(id);
            })
            if(barcode != ''){
                $.ajax({
                    url: `<?php echo e(route("ship.checkBarcode")); ?>`,
                    type: 'GET',
                    header:{
                        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'barcode': barcode,
                        'product_id' : product_id 
                    },
                    cache: false,
                    success: function (data) {
                        if(data.message.product_id){
                            var productFound = data.message.product_id;
                            $('.productId').each(function() {
                                var id = $(this).val();
                                var usedQty = parseInt($('.useQty_' + productFound).text());
                                if(parseInt(id) === parseInt(productFound)){
                                    var getqty = $('.qtyhandle_coded_' + productFound).val();
                                    var newQty = parseInt(getqty) + 1; 
                                    if(usedQty >= newQty){
                                        $('.qtyhandle_coded_' + productFound).val(newQty);
                                    }else{
                                        show_toastr('error', 'Quantity already picked.'); 
                                    }
                                }
                            })
                        }else{
                            show_toastr('error', 'Barcode already used or not found.'); 
                        }
                    }
                });
            }else{
                show_toastr('error', 'Please add barcode.');
            }
        })
    </script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container">
       
        <div class="row">
            <div class="col-12">
                <div class="invoice">
                    <div class="invoice-print">
                        <div class="row mt-4">
                            <div class="col">
                                <strong><?php echo e(__('Order Number')); ?> </strong><br>
                                <input type="text" class="form-control" value="<?php echo e($posShip->ship_unique); ?>" readonly>
                            </div>
                            <div class="col">
                                <strong><?php echo e(__('Customer')); ?></strong><br>
                                <input type="text" class="form-control" value="<?php echo e($customer->name); ?>" readonly>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="font-bold mb-2"><?php echo e(__('Product quantity using barcode')); ?></div>
                                <div class="row">
                                    <div class="col-4">
                                        <div class="d-flex">
                                            <input type="text" class="form-control" id="barcodeInput" placeholder="Barcode">
                                            <button class="text-end btn btn-success me-2" id="addQtyByBarcode">
                                                <?php echo e(__('Add')); ?>

                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <?php echo e(Form::model($posShip, array('route' => array('ship.addPickingData', $posShip->id), 'method' => 'POST','class'=>'w-100'))); ?>

                                <div class="font-bold mb-2 mt-4"><?php echo e(__('Product Summary')); ?></div>
                                <div class="table-responsive mt-3">
                                    <table class="table">
                                        <tr>
                                            <th class="text-dark" data-width="40">#</th>
                                            <th class="text-dark"><?php echo e(__('Product')); ?></th>
                                            <th class="text-dark"><?php echo e(__('Quantity')); ?></th>
                                            <th class="text-dark"><?php echo e(__('Required Quantity')); ?></th>
                                        </tr>
                                        <?php if(count($iteams) > 0): ?>
                                            <?php $__currentLoopData = $iteams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key =>$iteam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e($key+1); ?></td>
                                                    <td>
                                                        <?php echo e(!empty($iteam->product())?$iteam->product()->name:''); ?> 
                                                        <input type="hidden" name="product_id[]" class="productId" value="<?php echo e($iteam->product()->id); ?>"/>
                                                    </td>
                                                    <td class="useQty_<?php echo e($iteam->product()->id); ?>"><?php echo e($iteam->quantity); ?></td>
                                                    <?php if($iteam->product()->product_type == 'barcode'): ?>
                                                        <td class="remainQty"><input class="qtyInput qtyhandle_coded_<?php echo e($iteam->product()->id); ?> form-control" type="number" name="remainQty[]" value="0" readonly/>
                                                        </td>
                                                    <?php else: ?>
                                                        <td class="remainQty"><input class="qtyInput qtyhandle_<?php echo e($iteam->product()->id); ?> form-control" type="number" name="remainQty[]" value="0"/>
                                                        </td>    
                                                    <?php endif; ?>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="4">No Product found.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </table>
                                    <div class="float-right mb-3">
                                        <input type="submit" value="<?php echo e(__('Pick')); ?>" class="text-end btn btn-success rounded me-2">
                                    </div>
                                </div>
                                <?php echo e(Form::close()); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/hp/Documents/CNS/idea/resources/views/ship/picking.blade.php ENDPATH**/ ?>