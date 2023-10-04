<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage Product Price List')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('script-page'); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Product Price List')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th><?php echo e(__('Product Code')); ?></th>
                                <th><?php echo e(__('Name')); ?></th>
                                <th><?php echo e(__('Description')); ?></th>
                                <th><?php echo e(__('MSP')); ?></th>
                                <th><?php echo e(__('LSP')); ?></th>
                                <th><?php echo e(__('ALLIANCE')); ?></th>
                                <th><?php echo e(__('PREMIUM')); ?></th>
                                <th><?php echo e(__('STANDARD')); ?></th>
                            </tr>
                            </thead>
                            <tbody id="updateData">
                            <?php $__currentLoopData = $sellingPriceList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $productPrice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="font-style">
                                    <td><?php echo e($productPrice->sku); ?></td>
                                    <td><?php echo e($productPrice->name); ?></td>
                                    <td><?php echo e(Illuminate\Support\Str::limit($productPrice->description, 15)); ?></td>
                                    <td><?php echo e($productPrice->msp_margin); ?></td>
                                    <td><?php echo e($productPrice->lsp_margin); ?></td>
                                    <td><?php echo e($productPrice->alliance); ?></td>
                                    <td><?php echo e($productPrice->premium); ?></td>
                                    <td><?php echo e($productPrice->standard); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/earthdlp/admin.imontechnologies.in/resources/views/productservice/productPriceList.blade.php ENDPATH**/ ?>