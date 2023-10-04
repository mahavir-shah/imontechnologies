<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="invoice">
                <div class="invoice-print">
                    <div class="row">
                        <div class="col">
                            <small>
                                <strong><?php echo e(__('Sale Id')); ?> :</strong><br>
                                <?php echo e(Auth::user()->posNumberFormat($pos->pos_id)); ?><br>
                            </small>
                        </div>
                        <?php if(!empty($vendor->billing_name)): ?>
                            <div class="col">
                                <small class="font-style">
                                    <strong><?php echo e(__('Billed To')); ?> :</strong><br>
                                    <?php echo e(!empty($vendor->billing_name)?$vendor->billing_name:''); ?><br>
                                </small>
                            </div>
                        <?php endif; ?>
                        <?php if(App\Models\Utility::getValByName('shipping_display')=='on'): ?>
                            <div class="col">
                                <small>
                                    <strong><?php echo e(__('Shipped To')); ?> :</strong><br>
                                    <?php echo e(!empty($vendor->shipping_name)?$vendor->shipping_name:''); ?><br>
                                </small>
                            </div>
                        <?php endif; ?>
                        <div class="col">
                            <small>
                                <strong><?php echo e(__('Issue Date')); ?> :</strong><br>
                                <?php echo e(\Auth::user()->dateFormat($pos->created_at)); ?><br>
                            </small>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="font-bold mb-2"><?php echo e(__('Product Summary')); ?></div>
                            <div class="table-responsive mt-3">
                                <table class="table ">
                                    <tr>
                                        <th class="text-dark" data-width="40">#</th>
                                        <th class="text-dark"><?php echo e(__('Product')); ?></th>
                                        <th class="text-dark"><?php echo e(__('Quantity')); ?></th>
                                        <th class="text-dark"><?php echo e(__('Rate')); ?></th>
                                        
                                        <th class="text-dark" width="12%"><?php echo e(__('Price')); ?></th>
                                    </tr>
                                    <?php $__currentLoopData = $iteams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key =>$iteam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($key+1); ?></td>
                                            <td><?php echo e(!empty($iteam->product())?$iteam->product()->name:''); ?></td>
                                            <td><?php echo e($iteam->quantity); ?></td>
                                            <td><?php echo e(\Auth::user()->priceFormat($iteam->price)); ?></td>
                                            
                                            <td class="text-end"><?php echo e(\Auth::user()->priceFormat(($iteam->price*$iteam->quantity))); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <tfoot>
                                    <tr>
                                        <td colspan="3"></td>
                                        <td class="text-end"><b><?php echo e(__('Discount')); ?></b></td>
                                        <td class="text-end"><?php echo e(\Auth::user()->priceFormat($posPayment->discount)); ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"></td>
                                        <td class="text-end"><b><?php echo e(__('Total')); ?></b></td>
                                        <td class="text-end"><?php echo e(\Auth::user()->priceFormat($pos->getDue())); ?></td>
                                    </tr>
                                    </tfoot>
                                </table>
                                <div class="float-right mb-3">
                                    <a href="<?php echo e(route('pos.sales_approve',$pos->pos_id)); ?>" class="text-end btn btn-success btn-done-payment rounded me-2">
                                        <?php echo e(__('Accept Order')); ?>

                                    </a>
                                    <a href="<?php echo e(route('pos.sales_cancel',$pos->pos_id)); ?>" class="text-end btn btn-danger btn-done-payment rounded">
                                        <?php echo e(__('Cancel Order')); ?>

                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><?php /**PATH /home/hp/Documents/CNS/idea/resources/views/pos/showApprove.blade.php ENDPATH**/ ?>