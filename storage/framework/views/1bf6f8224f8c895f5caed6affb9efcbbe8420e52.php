<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('POS Product Barcode')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('POS Product Barcode')); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('css-page'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/datatable/buttons.dataTables.min.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('action-btn'); ?>
    <div class="float-end">
        <a data-url="<?php echo e(route('pos.getBarcodePrint')); ?>" data-ajax-popup="true" data-bs-toggle="tooltip" data-title="<?php echo e(__('Search Barcode')); ?>" title="<?php echo e(__('Search Barcode And Print')); ?>" class="btn btn-sm btn-primary">
            <i class="ti ti-search text-white"></i>
        </a>
        <a data-url="<?php echo e(route('pos.setting')); ?>" data-ajax-popup="true" data-bs-toggle="tooltip" data-title="<?php echo e(__('Barcode Setting')); ?>" title="<?php echo e(__('Barcode Setting')); ?>" class="btn btn-sm btn-primary">
            <i class="ti ti-settings text-white"></i>
        </a>

    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive ">
                        <table class="table datatable-barcode" >
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Purchase')); ?></th>
                                    <th><?php echo e(__('Barcode')); ?></th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $purchase; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e(Auth::user()->purchaseNumberFormat($item->id)); ?></td>
                                        <td>
                                            <a href="<?php echo e(route('pos.print', $item->id)); ?>" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="<?php echo e(__('Print Barcode')); ?>">
                                                <i class="ti ti-scan text-white"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-dark"><p><?php echo e(__('No Data Found')); ?></p></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script-page'); ?>

    <script src="<?php echo e(asset('public/js/jquery-barcode.js')); ?>"></script>
    <script>

        setTimeout(myGreeting, 1000);
        function myGreeting() {
            if ($(".datatable-barcode").length > 0) {
                const dataTable =  new simpleDatatables.DataTable(".datatable-barcode");
            }
        }
        // });
    </script>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/hp/Documents/CNS/idea/resources/views/pos/barcode.blade.php ENDPATH**/ ?>