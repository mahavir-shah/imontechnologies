<div class="modal-body">
    <div class="card ">
        <div class="card-body table-border-style full-card">
            <div class="table-responsive">
                
                <table class="table">
                    <thead>
                    <tr>
                        <th><?php echo e(__('Warehouse')); ?></th>
                        <th><?php echo e(__('Quantity')); ?></th>

                    </tr>
                    </thead>
                    <tbody>

                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(!empty($product->warehouse())): ?>
                            <tr>
                                <td><?php echo e(!empty($product->warehouse())?$product->warehouse()->name:'-'); ?></td>
                                <td><?php echo e($product->quantity); ?></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                
                
                
                
                
            </div>
        </div>
    </div>
</div>
<?php /**PATH /home1/earthdlp/admin.imontechnologies.in/resources/views/productservice/detail.blade.php ENDPATH**/ ?>