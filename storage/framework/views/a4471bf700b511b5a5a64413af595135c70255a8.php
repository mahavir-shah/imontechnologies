<?php
    //$profile=asset(Storage::url('uploads/avatar/'));
    $profile=\App\Models\Utility::get_file('uploads/avatar/');
?>
<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage Client')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('script-page'); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('clients.index')); ?>"><?php echo e(__('Client')); ?></a></li>
    <li class="breadcrumb-item">  <?php echo e(ucwords($client->name).__("'s Detail")); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('action-btn'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="row">
        <div class="col-sm-3">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mb-3 mb-sm-0">
                            <small class="text-muted"><?php echo e(__('Total Estimate')); ?></small>
                            <h3 class="m-0"><?php echo e($cnt_estimation['total']); ?> / <?php echo e($cnt_estimation['cnt_total']); ?></h3>
                        </div>
                        <div class="col-auto">
                            <div class="theme-avtar bg-info">
                                <i class="ti ti-coin"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mb-3 mb-sm-0">
                            <small class="text-muted"><?php echo e(__('This Month Total Estimate')); ?></small>
                            <h3 class="m-0"><?php echo e($cnt_estimation['this_month']); ?> / <?php echo e($cnt_estimation['cnt_this_month']); ?></h3>
                        </div>
                        <div class="col-auto">
                            <div class="theme-avtar bg-primary">
                                <i class="ti ti-coin"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mb-3 mb-sm-0">
                            <small class="text-muted"><?php echo e(__('This Week Total Estimate')); ?></small>
                            <h3 class="m-0"><?php echo e($cnt_estimation['this_week']); ?> / <?php echo e($cnt_estimation['cnt_this_week']); ?></h3>
                        </div>
                        <div class="col-auto">
                            <div class="theme-avtar bg-warning">
                                <i class="ti ti-coin"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mb-3 mb-sm-0">
                            <small class="text-muted"><?php echo e(__('Last 30 Days Total Estimate')); ?></small>
                            <h3 class="m-0"><?php echo e($cnt_estimation['last_30days']); ?> / <?php echo e($cnt_estimation['cnt_last_30days']); ?></h3>
                        </div>
                        <div class="col-auto">
                            <div class="theme-avtar bg-danger">
                                <i class="ti ti-coin"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>

                                <th><?php echo e(__('Estimate')); ?></th>
                                <th><?php echo e(__('Client')); ?></th>
                                <th><?php echo e(__('Issue Date')); ?></th>
                                <th><?php echo e(__('Value')); ?></th>
                                <th><?php echo e(__('Status')); ?></th>
                                <?php if(Auth::user()->type != 'client'): ?>
                                    <th width="250px"><?php echo e(__('Action')); ?></th>
                                <?php endif; ?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__currentLoopData = $estimations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estimate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="Id">
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('View Estimation')): ?>
                                            <a href="<?php echo e(route('estimations.show',$estimate->id)); ?>"> <i class="ti ti-file-estimate"></i> <?php echo e(Auth::user()->estimateNumberFormat($estimate->estimation_id)); ?></a>
                                        <?php else: ?>
                                            <?php echo e(Auth::user()->estimateNumberFormat($estimate->estimation_id)); ?>

                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($estimate->client->name); ?></td>
                                    <td><?php echo e(Auth::user()->dateFormat($estimate->issue_date)); ?></td>
                                    <td><?php echo e(Auth::user()->priceFormat($estimate->getTotal())); ?></td>
                                    <td>
                                        <?php if($estimate->status == 0): ?>
                                            <span class="badge badge-pill badge-primary"><?php echo e(__(\App\Models\Estimation::$statues[$estimate->status])); ?></span>
                                        <?php elseif($estimate->status == 1): ?>
                                            <span class="badge badge-pill badge-danger"><?php echo e(__(\App\Models\Estimation::$statues[$estimate->status])); ?></span>
                                        <?php elseif($estimate->status == 2): ?>
                                            <span class="badge badge-pill badge-warning"><?php echo e(__(\App\Models\Estimation::$statues[$estimate->status])); ?></span>
                                        <?php elseif($estimate->status == 3): ?>
                                            <span class="badge badge-pill badge-success"><?php echo e(__(\App\Models\Estimation::$statues[$estimate->status])); ?></span>
                                        <?php elseif($estimate->status == 4): ?>
                                            <span class="badge badge-pill badge-info"><?php echo e(__(\App\Models\Estimation::$statues[$estimate->status])); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <?php if(Auth::user()->type != 'client'): ?>
                                        <td class="Action">
                                        <span>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('View Estimation')): ?>
                                                <a href="<?php echo e(route('estimations.show',$estimate->id)); ?>" class="edit-icon bg-warning" data-bs-toggle="tooltip" data-original-title="<?php echo e(__('View')); ?>"><i class="ti ti-eye"></i></a>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Edit Estimation')): ?>
                                                <a href="#" data-url="<?php echo e(URL::to('estimations/'.$estimate->id.'/edit')); ?>" data-ajax-popup="true" data-title="<?php echo e(__('Edit Estimation')); ?>" class="edit-icon" data-bs-toggle="tooltip" data-original-title="<?php echo e(__('Edit')); ?>"><i class="ti ti-pencil text-white"></i></a>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Delete Estimation')): ?>
                                                <a href="#" class="delete-icon" data-bs-toggle="tooltip" data-original-title="<?php echo e(__('Delete')); ?>" data-confirm="<?php echo e(__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')); ?>" data-confirm-yes="document.getElementById('delete-form-<?php echo e($estimate->id); ?>').submit();"><i class="ti ti-trash"></i></a>
                                                <?php echo Form::open(['method' => 'DELETE', 'route' => ['estimations.destroy', $estimate->id],'id'=>'delete-form-'.$estimate->id]); ?>

                                                <?php echo Form::close(); ?>

                                            <?php endif; ?>
                                        </span>
                                        </td>
                                    <?php endif; ?>
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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/earthdlp/admin.imontechnologies.in/resources/views/clients/show.blade.php ENDPATH**/ ?>