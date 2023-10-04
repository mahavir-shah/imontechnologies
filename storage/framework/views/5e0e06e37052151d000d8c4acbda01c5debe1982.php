<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="invoice">
                <div class="invoice-print">
                    <div class="row">
                        <div class="col">
                            <small>
                                <strong><?php echo e(__('Order Number')); ?> :</strong><br>
                                <?php echo e($posShip->ship_unique); ?><br>
                            </small>
                        </div>
                        <div class="col">
                            <small>
                                <strong><?php echo e(__('Customer')); ?> :</strong><br>
                                <?php echo e($customer->name); ?><br>
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
                                </table>
                                <div class="float-right mb-3">
                                    <a href="<?php echo e(route('ship.addPicking',$posShip->id)); ?>" class="text-end btn btn-success btn-done-payment rounded me-2">
                                        <?php echo e(__('Pick')); ?>

                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><?php /**PATH /home1/earthdlp/admin.imontechnologies.in/resources/views/ship/picking.blade.php ENDPATH**/ ?>