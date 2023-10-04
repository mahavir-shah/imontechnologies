<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Job Details')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('job.index')); ?>"><?php echo e(__('Job')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Job Details')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('action-btn'); ?>
    <div class="float-end">
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit job')): ?>
            <a href="<?php echo e(route('job.edit',$job->id)); ?>" data-url="" data-ajax-popup="true" data-title="<?php echo e(__('Edit Job')); ?>" data-bs-toggle="tooltip" title="<?php echo e(__('Edit')); ?>"  class="btn btn-sm btn-primary">
                <i class="ti ti-pencil"></i>
            </a>

        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-4">
            <div class="card ">
            <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mt-3">
                            <tbody>
                            <tr>
                                <td><?php echo e(__('Job Title')); ?></td>
                                <td class=""><?php echo e($job->title); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo e(__('Branch')); ?></td>
                                <td class=""><?php echo e(!empty($job->branches)?$job->branches->name:__('All')); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo e(__('Job Category')); ?></td>
                                <td class=""><?php echo e(!empty($job->categories)?$job->categories->title:'-'); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo e(__('Positions')); ?></td>
                                <td class=""><?php echo e($job->position); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo e(__('Status')); ?></td>
                                <td class="">
                                    <?php if($job->status=='active'): ?>
                                        <span class="p-2 px-3 rounded badge bg-success"><?php echo e(App\Models\Job::$status[$job->status]); ?></span>
                                    <?php else: ?>
                                        <span class="p-2 px-3 rounded badge bg-danger"><?php echo e(App\Models\Job::$status[$job->status]); ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo e(__('Created Date')); ?></td>
                                <td class=""><?php echo e(\Auth::user()->dateFormat($job->created_at)); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo e(__('Start Date')); ?></td>
                                <td class=""><?php echo e(\Auth::user()->dateFormat($job->start_date)); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo e(__('End Date')); ?></td>
                                <td class=""><?php echo e(\Auth::user()->dateFormat($job->end_date)); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo e(__('Skill')); ?></td>
                                <td class="">
                                    <?php $__currentLoopData = $job->skill; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span class="p-2 px-3 rounded badge bg-primary"><?php echo e($skill); ?></span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </td>
                            </tr>

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card card-fluid">
                <div class="card-body">
                    <div class="col-12">
                        <div class="row">

                            <?php if(($job->applicant)): ?>
                                <div class="col-6">
                                    <h6><?php echo e(__('Need to ask ?')); ?></h6>
                                    <ul class="">
                                        <?php $__currentLoopData = $job->applicant; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $applicant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><?php echo e(ucfirst($applicant)); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            <?php if(!empty($job->visibility)): ?>
                                <div class="col-6">
                                    <h6><?php echo e(__('Need to show option ?')); ?></h6>
                                    <ul class="">
                                        <?php $__currentLoopData = $job->visibility; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $visibility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><?php echo e(ucfirst($visibility)); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>

                                </div>
                            <?php endif; ?>

                            <?php if(count($job->questions())>0): ?>
                                <div class="col-12">
                                    <h6><?php echo e(__('Custom Question')); ?></h6>
                                    <ul class="">
                                        <?php $__currentLoopData = $job->questions(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><?php echo e($question->question); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="row ">
                            <div class="col-12 mt-3">
                                <h6><?php echo e(__('Job Description')); ?></h6>
                                <?php echo $job->description; ?>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mt-3">
                                <h6><?php echo e(__('Job Requirement')); ?></h6>
                                <?php echo $job->requirement; ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/earthdlp/admin.imontechnologies.in/resources/views/job/show.blade.php ENDPATH**/ ?>