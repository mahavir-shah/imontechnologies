<div class="modal-body">
    <div class="card ">
        <div class="card-body table-border-style full-card">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th><?php echo e(__('Name')); ?></th>
                            <th><?php echo e(__('Price')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo e($productKit->kit_name); ?></td>
                            <td><?php echo e($productKit->kit_price); ?></td>
                        </tr>
                    </tbody>
                </table>
                <table class="table">
                    <thead>
                        <tr>
                            <th><?php echo e(__('Product Name')); ?></th>
                            <th><?php echo e(__('Modal')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($product->name); ?></td>
                                <td><?php echo e($product->sku); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php /**PATH /home/vsgpintl/admin.imontechnologies.in/resources/views/productkit/show.blade.php ENDPATH**/ ?>