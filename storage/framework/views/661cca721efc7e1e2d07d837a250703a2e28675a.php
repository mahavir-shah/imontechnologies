<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage Terms Condition')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('script-page'); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Terms Condition')); ?></li>
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
                                <th><?php echo e(__('Customer Type')); ?></th>
                                <th><?php echo e(__('Terms Condition')); ?></th>
                                <th><?php echo e(__('Action')); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__currentLoopData = $termscondition; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $termscon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="font-style">
                                    <td><?php echo e($termscon->customer_type); ?></td>
                                    <td width="60px"><?php echo e($termscon->content); ?></td>
									<td class="Action">
									   <div class="action-btn bg-info ms-2">
											<a href="<?php echo e(route('termscondition.edit',$termscon->id)); ?>" class="mx-3 btn btn-sm  align-items-center" title="<?php echo e(__('Edit')); ?>"  data-title="<?php echo e(__('Terms Condition Edit')); ?>">
												<i class="ti ti-pencil text-white"></i>
											</a>
										</div>
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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/imontechnologies/resources/views/terms_conditions/index.blade.php ENDPATH**/ ?>