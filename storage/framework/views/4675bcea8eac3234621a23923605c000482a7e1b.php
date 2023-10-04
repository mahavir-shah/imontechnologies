<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Stock Tracking Report')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('css-page'); ?>
    <style>
        .trackTable td{
            border: 1px solid #c6c6ca57;
        }
    </style>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Stock Tracking')); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('action-btn'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-sm-12">
            <div class=" mt-2 <?php echo e(isset($_GET['stocktrack'])?'show':''); ?>" id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        <?php echo e(Form::open(['route' => ['stocktrack.index'], 'method' => 'GET', 'id' => 'stocktrack'])); ?>

                        <div class="d-flex align-items-center">
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 me-2">
                                <div class="btn-box">
                                    <?php echo e(Form::label('model_no', __('Model No'),['class'=>'form-label'])); ?>

                                    <?php echo e(Form::text('model_no', null, ['class' => 'form-control', 'required' => 'required'])); ?>

                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                <div class="btn-box">
                                    <?php echo e(Form::label('barcode', __('Barcode'),['class'=>'form-label'])); ?>

                                    <?php echo e(Form::text('barcode', null, ['class' => 'form-control','required' => 'required'])); ?>

                                </div>
                            </div>
                            <div class="col-auto float-end ms-2 mt-4">
                                <a href="#" class="btn btn-sm btn-primary"
                                   onclick="document.getElementById('stocktrack').submit(); return false;"
                                   data-bs-toggle="tooltip" title="<?php echo e(__('apply')); ?>">
                                    <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                </a>
                                <a href="<?php echo e(route('stocktrack.index')); ?>" class="btn btn-sm btn-danger" data-bs-toggle="tooltip"
                                   title="<?php echo e(__('Reset')); ?>">
                                    <span class="btn-inner--icon"><i class="ti ti-trash-off "></i></span>
                                </a>
                            </div>

                        </div>
                        <?php echo e(Form::close()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable trackTable">
                            <thead>
                            <tr>
                                <th><?php echo e(__('Transaction Date')); ?></th>
                                <th><?php echo e(__('Name')); ?></th>
                                <th><?php echo e(__('Model No')); ?></th>
                                <th><?php echo e(__('Purchase')); ?></th>
                                <th><?php echo e(__('Purchase Return')); ?></th>
                                <th><?php echo e(__('Sales')); ?></th>
                                <th><?php echo e(__('Sales Return')); ?></th>
                                <th><?php echo e(__('Replace In')); ?></th>
                                <th><?php echo e(__('Replace Out')); ?></th>
                                <th><?php echo e(__('Repair In')); ?></th>
                                <th><?php echo e(__('Repair Out')); ?></th>
                                <th><?php echo e(__('Repaired In')); ?></th>
                                <th><?php echo e(__('Repaired Out')); ?></th>
                                <th><?php echo e(__('Return to Client')); ?></th>
                                <th><?php echo e(__('Executive')); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__currentLoopData = $productServices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $productService): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="font-style">
                                    <td><?php echo e(\Auth::user()->dateFormat($productService->created_at)); ?></td>
                                    <td><?php echo e($productService->name); ?></td>
                                    <td><?php echo e($productService->sku); ?></td>
                                    <td><?php echo e($productService->purchase ? 1 : ''); ?></td>
                                    <td><?php echo e($productService->purchase_return ? 1 : ''); ?></td>
                                    <td><?php echo e($productService->sales ? 1 : ''); ?></td>
                                    <td><?php echo e($productService->sales_return ? 1 : ''); ?></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/imontechnologies/resources/views/productstock/stockTracking.blade.php ENDPATH**/ ?>