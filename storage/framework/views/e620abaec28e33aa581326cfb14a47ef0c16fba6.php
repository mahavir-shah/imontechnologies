<?php
   // $profile=asset(Storage::url('uploads/avatar/'));
    $profile=\App\Models\Utility::get_file('uploads/avatar/');
?>
<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage Discount Master')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('script-page'); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Discount Master')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th> <?php echo e(__('Id')); ?></th>
                                <th> <?php echo e(__('Client Type')); ?></th>
                                <th> <?php echo e(__('Discount')); ?></th>
                                <th> <?php echo e(__('Payment Days')); ?></th>
                                <th><?php echo e(__('Transaction Limit')); ?></th>
                                <th><?php echo e(__('Tread Discount')); ?></th>
                                <th> <?php echo e(__('Action')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $discountMaster; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($client->id); ?></td>
                                    <td> <?php echo e($client->client_type); ?> </td>
                                    <td> <?php echo e($client->discount . '%'); ?> </td>
                                    <td> <?php echo e($client->payment); ?> </td>
                                    <td> <?php echo e($client->transaction_limit); ?> </td>
                                    <td> <?php echo e($client->tread_discount . '%'); ?> </td>
                                    <td class="Action">
                                        <span>
                                            <div class="action-btn bg-primary ms-2">
                                                <a href="#!" data-size="md" data-url="<?php echo e(route('discount-master.edit',\Crypt::encrypt($client->id))); ?>" data-ajax-popup="true" class="mx-3 btn btn-sm align-items-center" data-bs-original-title="<?php echo e(__('Edit')); ?>">
                                                    <i class="ti ti-pencil text-white"></i>
                                                </a>
                                            </div>
                                        </span>
                                    </td>
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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/earthdlp/admin.imontechnologies.in/resources/views/discountMaster/index.blade.php ENDPATH**/ ?>