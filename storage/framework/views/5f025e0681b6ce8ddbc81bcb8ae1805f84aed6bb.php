<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Sales')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Sales')); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('css-page'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/datatable/buttons.dataTables.min.css')); ?>">
    <style>
        .float-right{
            float: right !important;
        }
        .text-bold{
            font-weight: 900 !important;
        }
        .text-opacity{
            opacity: 0.6;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script-page'); ?>
    <script type="text/javascript" src="<?php echo e(asset('js/html2pdf.bundle.min.js')); ?>"></script>
    <script>

        var filename = $('#filename').val();

        function saveAsPDF() {
            var element = document.getElementById('printableArea');
            var opt = {
                margin: 0.3,
                filename: filename,
                image: {type: 'jpeg', quality: 1},
                html2canvas: {scale: 4, dpi: 72, letterRendering: true},
                jsPDF: {unit: 'in', format: 'A2'}
            };
            html2pdf().set(opt).from(element).toPdf().get('pdf').then(function (pdf) {
                window.open(pdf.output('bloburl'), '_blank');
            });
        }
    </script>

<?php $__env->stopPush(); ?>

<?php $__env->startSection('action-btn'); ?>
    <div class="float-end">
        <a href="#" class="btn btn-sm btn-primary" onclick="saveAsPDF()"data-bs-toggle="tooltip" title="<?php echo e(__('Download')); ?>" data-original-title="<?php echo e(__('Download')); ?>">
            <span class="btn-inner--icon"><i class="ti ti-download"></i></span>
        </a>
    </div>


<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
    <div id="printableArea">

    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th><?php echo e(__('SALE ID')); ?></th>
                                <th><?php echo e(__('Customer')); ?></th>
                                <th><?php echo e(__('Sales Date')); ?></th>
                                <th><?php echo e(__('Due Date')); ?></th>
                                <th><?php echo e(__('Amount')); ?></th>
                                <?php if(Auth::user()->type == 'company'): ?>
                                <th><?php echo e(__('Delivery Status')); ?></th>
                                <th><?php echo e(__('Status')); ?></th>
                                <?php endif; ?>
                                <?php if(Auth::user()->type == 'customer'): ?>
                                <th> <?php echo e(__('Status')); ?></th>
                                <?php endif; ?>
                                <th> <?php echo e(__('Action')); ?></th>
                            </tr>
                            </thead>

                            <tbody>

                            <?php $__empty_1 = true; $__currentLoopData = $posPayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $posPayment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e(Auth::user()->posNumberFormat($posPayment->pos_id)); ?></td>
                                    <?php if($posPayment->customer_id == 0): ?>
                                        <td class=""><?php echo e(__('Walk-in Customer')); ?></td>
                                    <?php else: ?>
                                        <td><?php echo e(!empty($posPayment->customer) ? $posPayment->customer->name : ''); ?> </td>
                                    <?php endif; ?>
                                    <td><?php echo e(Auth::user()->dateFormat($posPayment->created_at)); ?></td>
                                    <td><?php echo e($posPayment->pos_date != '0000-00-00' &&  $posPayment->pos_date != null ? Auth::user()->dateFormat($posPayment->pos_date) : '-'); ?></td>
                                    <td><?php echo e(!empty($posPayment->posPayment)? \Auth::user()->priceFormat($posPayment->posPayment->discount_amount) :0); ?></td>
                                    <?php if(Auth::user()->type == 'company'): ?>
                                        <td>
                                            <?php if(isset($deliveryStatus[$key]) && $deliveryStatus[$key]['status'] == 'pending'): ?>
                                                <span class="purchase_status badge p-2 px-3 rounded bg-secondary"> <?php echo e(ucfirst($deliveryStatus[$key]['status'])); ?> </span>
                                            <?php elseif(isset($deliveryStatus[$key]) && $deliveryStatus[$key]['status'] == 'picking'): ?>
                                                <span class="purchase_status badge p-2 px-3 rounded bg-warning"> <?php echo e(ucfirst($deliveryStatus[$key]['status'])); ?> </span>
                                            <?php elseif(isset($deliveryStatus[$key]) && $deliveryStatus[$key]['status'] == 'packing'): ?>
                                                <span class="purchase_status badge p-2 px-3 rounded bg-info"> <?php echo e(ucfirst($deliveryStatus[$key]['status'])); ?> </span>
                                            <?php elseif(isset($deliveryStatus[$key]) && $deliveryStatus[$key]['status'] == 'shipped'): ?>
                                                <span class="purchase_status badge p-2 px-3 rounded bg-success"> <?php echo e(ucfirst($deliveryStatus[$key]['status'])); ?> </span> 
                                            <?php else: ?>
                                                <span class="purchase_status badge p-2 px-3 rounded bg-gray-500"> <?php echo e('Not Added'); ?> </span>   
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($posPayment->status == 1): ?>
                                                <span class="purchase_status badge p-2 px-3 rounded bg-warning"> <?php echo e(__('Approved')); ?> </span>
                                            <?php elseif($posPayment->status == 2): ?>
                                                <span class="purchase_status badge p-2 px-3 rounded bg-danger"> <?php echo e(__('Cancel')); ?> </span>
                                            <?php else: ?> 
                                                <span class="purchase_status badge p-2 px-3 rounded bg-secondary"> <?php echo e(__('Not Approved')); ?> </span>
                                            <?php endif; ?>
                                        </td>
                                    <?php endif; ?>
                                    <?php if(Auth::user()->type == 'customer'): ?>
                                    <td>
                                        <?php if($posPayment->status == 1): ?>
                                            <span class="purchase_status badge p-2 px-3 rounded bg-warning"> <?php echo e(__('Approved')); ?> </span>
                                        <?php elseif($posPayment->status == 2): ?>
                                            <span class="purchase_status badge p-2 px-3 rounded bg-danger"> <?php echo e(__('Cancel')); ?> </span>
                                        <?php else: ?> 
                                            <span class="purchase_status badge p-2 px-3 rounded bg-secondary"> <?php echo e(__('Not Approved')); ?> </span>
                                        <?php endif; ?>
                                    </td>
                                    <?php endif; ?>
                                    <td>
                                        <?php if(Auth::user()->type == 'company'): ?>
                                            <?php if($posPayment->status == 0): ?>
                                                <?php if($posPayment->customer_limit == 'advance_payment' && !in_array($posPayment->id,$invoiceStatus)): ?>
                                                    <div class="action-btn bg-warning ms-2">
                                                        <button class="mx-3 btn btn-sm align-items-center disabled" title="<?php echo e(__('Approve')); ?>" data-bs-toggle="tooltip" data-original-title="<?php echo e(__('Approve')); ?>" data-ajax-popup="true" data-size="lg"
                                                        data-align="centered" data-url="<?php echo e(route('pos.show_approve_detail',\Crypt::encrypt($posPayment->id))); ?>" data-title="<?php echo e(__('Order Detail')); ?>" disabled="disabled"><i class="ti ti-check text-white text-opacity"></i></button>
                                                    </div>
                                                <?php elseif($posPayment->customer_limit == 'credit_line' || ($posPayment->customer_limit == 'advance_payment' && in_array($posPayment->id,$invoiceStatus))): ?>
                                                    <div class="action-btn bg-warning ms-2">
                                                        <button class="mx-3 btn btn-sm align-items-center" title="<?php echo e(__('Approve')); ?>" data-bs-toggle="tooltip" data-original-title="<?php echo e(__('Approve')); ?>" data-ajax-popup="true" data-size="lg"
                                                        data-align="centered" data-url="<?php echo e(route('pos.show_approve_detail',\Crypt::encrypt($posPayment->id))); ?>" data-title="<?php echo e(__('Order Detail')); ?>"><i class="ti ti-check text-bold text-white"></i></button>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="action-btn bg-primary ms-2">
                                                    <a class="mx-3 btn btn-sm align-items-center" title="<?php echo e(__('Edit Order')); ?>" data-bs-toggle="tooltip" data-original-title="<?php echo e(__('Edit Order')); ?>" href="<?php echo e(route('pos.sales_edit',\Crypt::encrypt($posPayment->id))); ?>" data-title="<?php echo e(__('Edit Detail')); ?>"><i class="ti ti-pencil text-white"></i></a>
                                                </div>
                                                <?php if(!in_array($posPayment->id,$invoiceStatus)): ?>
                                                    <div class="action-btn bg-pink-400 ms-2">
                                                        <a href="#" data-url="<?php echo e(route('pos.payment',$posPayment->id)); ?>" data-ajax-popup="true" title="<?php echo e(__('Convert into Invoice')); ?>" class="mx-3 btn btn-sm align-items-center" data-title="<?php echo e(__('Add Payment')); ?>"><i class="ti ti-report-money mr-2 text-white"></i></a>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            <?php if($posPayment->status == 1): ?>
                                                <div class="action-btn bg-danger ms-2">
                                                    <?php echo Form::open(['method' => 'DELETE', 'route' => ['pos.sales_cancel', $posPayment->id],'class'=>'delete-form-btn','id'=>'delete-form-'.$posPayment->id]); ?>

                                                    <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip" title="<?php echo e(__('Cancel Order')); ?>" data-original-title="<?php echo e(__('Cancel Order')); ?>" data-confirm="<?php echo e(__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')); ?>" data-confirm-yes="document.getElementById('delete-form-<?php echo e($posPayment->id); ?>').submit();">
                                                        <i class="ti ti-trash text-white"></i>
                                                    </a>
                                                    <?php echo Form::close(); ?>

                                                </div>
                                                <?php if(!in_array($posPayment->id,$returnCompleted)): ?>
                                                    <div class="action-btn bg-purple-400 ms-2">
                                                        <a href="<?php echo e(route('pos.pos-return',$posPayment->id)); ?>" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="<?php echo e(__('Sales Return')); ?>" data-original-title="<?php echo e(__('Sales Return')); ?>">
                                                            <i class="ti ti-package text-white"></i>
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if(in_array($posPayment->id,$returnRecordsId)): ?>
                                                    <div class="action-btn bg-gray-500 ms-2">
                                                        <a href="<?php echo e(route('pos.return-records',$posPayment->id)); ?>" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="<?php echo e(__('Return records')); ?>" data-original-title="<?php echo e(__('Return records')); ?>">
                                                            <i class="ti ti-files text-white"></i>
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            <?php if(!in_array($posPayment->id,$shippingStatus) && $posPayment->status == 1): ?>
                                                <div class="action-btn bg-gray-500 ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm bs-pass-para-pos align-items-center" data-confirm="<?php echo e(__('Are You Sure?')); ?>" data-text="<?php echo e(__('This data will be go with pending pick list. Do you want to continue?')); ?>" data-confirm-yes="pending-pick" title="<?php echo e(__('Pick')); ?>" data-id="<?php echo e($posPayment->id); ?>">
                                                        <i class="ti ti-archive text-white"></i>
                                                    </a>
                                                    <?php echo Form::open(['method' => 'post', 'url' => ['pickpack/pending_pick'], 'id' => 'pending-pick']); ?>

                                                    <input type="hidden" name="id" value="<?php echo e($posPayment->id); ?>">
                                                    <input type="hidden" name="totalAmt" value="<?php echo e(!empty($posPayment->posPayment) ? $posPayment->posPayment->discount_amount : 0); ?>">
                                                    <?php echo Form::close(); ?>

                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php if(Auth::user()->type == 'customer'): ?>
                                            <?php if($posPayment->status == 0): ?>
                                                <div class="action-btn bg-danger ms-2">
                                                    <a class="mx-3 btn btn-sm align-items-center" title="<?php echo e(__('Cancel Order')); ?>" data-bs-toggle="tooltip" data-original-title="<?php echo e(__('Cancel Order')); ?>" href="<?php echo e(route('pos.sales_cancel',\Crypt::encrypt($posPayment->id))); ?>" data-title="<?php echo e(__('Order Detail')); ?>"><i class="ti ti-trash text-white"></i></a>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <div class="action-btn bg-info ms-2">
                                            <a href="<?php echo e(route('pos.get_invoice',\Crypt::encrypt($posPayment->id))); ?>" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="<?php echo e(__('Invoice')); ?>" data-original-title="<?php echo e(__('Invoice_Detail')); ?>">
                                                <i class="ti ti-eye text-white"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="text-center text-dark"><p><?php echo e(__('No Data Found')); ?></p></td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/hp/Documents/CNS/idea/resources/views/pos/report.blade.php ENDPATH**/ ?>