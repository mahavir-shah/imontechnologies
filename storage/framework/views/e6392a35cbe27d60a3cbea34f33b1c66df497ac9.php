<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Purchase Detail')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('script-page'); ?>
    <script>
        $(document).on('click', '#shipping', function () {
            var url = $(this).data('url');
            var is_display = $("#shipping").is(":checked");
            $.ajax({
                url: url,
                type: 'get',
                data: {
                    'is_display': is_display,
                },
                success: function (data) {
                    // console.log(data);
                }
            });
        })



    </script>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('purchase.index')); ?>"><?php echo e(__('Purchase')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(Auth::user()->purchaseNumberFormat($purchase->id)); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    

    <?php if(\Auth::user()->type=='company'): ?>
        <?php if($purchase->status!=0): ?>
            <div class="row justify-content-between align-items-center mb-3">
                <div class="col-md-12 d-flex align-items-center justify-content-between justify-content-md-end">








                    <div class="all-button-box mx-2">
                        <a href="<?php echo e(route('purchase.resent',$purchase->id)); ?>" class="btn btn-sm btn-primary">
                            <?php echo e(__('Resend Purchase')); ?>

                        </a>
                    </div>
                    <div class="all-button-box">
                        <a href="<?php echo e(route('purchase.pdf', Crypt::encrypt($purchase->id))); ?>" target="_blank" class="btn btn-sm btn-primary">
                            <?php echo e(__('Download')); ?>

                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    <?php endif; ?>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="invoice">
                        <div class="invoice-print">
                            <div class="row invoice-title mt-2">
                                <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12">
                                    <h4><?php echo e(__('Purchase')); ?></h4>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12 text-end">
                                    <h4 class="invoice-number"><?php echo e(Auth::user()->purchaseNumberFormat($purchase->id)); ?></h4>
                                </div>
                                <div class="col-12">
                                    <hr>
                                </div>
                            </div>



                            <div class="row">
                                <div class="col text-end">
                                    <div class="d-flex align-items-center justify-content-end">
                                        <div class="me-4">
                                            <small>
                                                <strong><?php echo e(__('Issue Date')); ?> :</strong><br>
                                                <?php echo e(\Auth::user()->dateFormat($purchase->purchase_date)); ?><br><br>
                                            </small>
                                        </div>

                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <?php if(!empty($vendor->billing_name)): ?>
                                    <div class="col">
                                        <small class="font-style">
                                            <strong><?php echo e(__('Billed To')); ?> :</strong><br>
                                            <?php echo e(!empty($vendor->billing_name)?$vendor->billing_name:''); ?><br>
                                            <?php echo e(!empty($vendor->billing_phone)?$vendor->billing_phone:''); ?><br>
                                            <?php echo e(!empty($vendor->billing_address)?$vendor->billing_address:''); ?><br>
                                            <?php echo e(!empty($vendor->billing_zip)?$vendor->billing_zip:''); ?><br>
                                            <?php echo e(!empty($vendor->billing_city)?$vendor->billing_city:'' .', '); ?> <?php echo e(!empty($vendor->billing_state)?$vendor->billing_state:'',', '); ?> <?php echo e(!empty($vendor->billing_country)?$vendor->billing_country:''); ?><br>
                                            <strong><?php echo e(__('Tax Number ')); ?> : </strong><?php echo e(!empty($vendor->tax_number)?$vendor->tax_number:''); ?>

                                        </small>
                                    </div>
                                <?php endif; ?>
                                <?php if(App\Models\Utility::getValByName('shipping_display')=='on'): ?>
                                    <div class="col">
                                        <small>
                                            <strong><?php echo e(__('Shipped To')); ?> :</strong><br>
                                            <?php echo e(!empty($vendor->shipping_name)?$vendor->shipping_name:''); ?><br>
                                            <?php echo e(!empty($vendor->shipping_phone)?$vendor->shipping_phone:''); ?><br>
                                            <?php echo e(!empty($vendor->shipping_address)?$vendor->shipping_address:''); ?><br>
                                            <?php echo e(!empty($vendor->shipping_zip)?$vendor->shipping_zip:''); ?><br>
                                            <?php echo e(!empty($vendor->shipping_city)?$vendor->shipping_city:'' .', '); ?> <?php echo e(!empty($vendor->shipping_state)?$vendor->shipping_state:'',', '); ?> <?php echo e(!empty($vendor->shipping_country)?$vendor->shipping_country:''); ?><br>
                                            <strong><?php echo e(__('Tax Number ')); ?> : </strong><?php echo e(!empty($vendor->tax_number)?$vendor->tax_number:''); ?>


                                        </small>
                                    </div>
                                <?php endif; ?>

                                <div class="col">
                                    <div class="float-end mt-3">

                                        <?php echo DNS2D::getBarcodeHTML(route('purchase.link.copy',\Illuminate\Support\Facades\Crypt::encrypt($purchase->id)), "QRCODE",2,2); ?>

                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <small>
                                        <strong><?php echo e(__('Status')); ?> :</strong><br>
                                        <?php if($purchase->status == 0): ?>
                                            <span class="badge bg-secondary p-2 px-3 rounded"><?php echo e(__(\App\Models\Purchase::$statues[$purchase->status])); ?></span>
                                        <?php elseif($purchase->status == 1): ?>
                                            <span class="badge bg-warning p-2 px-3 rounded"><?php echo e(__(\App\Models\Purchase::$statues[$purchase->status])); ?></span>
                                        <?php elseif($purchase->status == 2): ?>
                                            <span class="badge bg-danger p-2 px-3 rounded"><?php echo e(__(\App\Models\Purchase::$statues[$purchase->status])); ?></span>
                                        <?php elseif($purchase->status == 3): ?>
                                            <span class="badge bg-info p-2 px-3 rounded"><?php echo e(__(\App\Models\Purchase::$statues[$purchase->status])); ?></span>
                                        <?php elseif($purchase->status == 4): ?>
                                            <span class="badge bg-success p-2 px-3 rounded"><?php echo e(__(\App\Models\Purchase::$statues[$purchase->status])); ?></span>
                                        <?php endif; ?>
                                    </small>
                                </div>




                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="font-bold mb-2"><?php echo e(__('Product Summary')); ?></div>
                                    <small class="mb-2"><?php echo e(__('All items here cannot be deleted.')); ?></small>
                                    <div class="table-responsive mt-3">
                                        <table class="table ">
                                            <tr>
                                                <th class="text-dark" data-width="40">#</th>
                                                <th class="text-dark"><?php echo e(__('Product')); ?></th>
                                                <th class="text-dark"><?php echo e(__('Quantity')); ?></th>
                                                <th class="text-dark"><?php echo e(__('Rate')); ?></th>
                                                <th class="text-dark"><?php echo e(__('Tax')); ?></th>
                                                <th class="text-dark">
                                                        <?php echo e(__('Discount')); ?>


                                                </th>
                                                <th class="text-dark"><?php echo e(__('Description')); ?></th>
                                                <th class="text-end text-dark" width="12%"><?php echo e(__('Price')); ?><br>
                                                    <small class="text-danger font-weight-bold"><?php echo e(__('before tax & discount')); ?></small>
                                                </th>
                                                <th></th>
                                            </tr>
                                            <?php
                                                $totalQuantity=0;
                                                $totalRate=0;
                                                $totalTaxPrice=0;
                                                $totalDiscount=0;
                                                $taxesData=[];
                                            ?>

                                            <?php $__currentLoopData = $iteams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key =>$iteam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if(!empty($iteam->tax)): ?>
                                                    <?php
                                                        $taxes=App\Models\Utility::tax($iteam->tax);
                                                        $totalQuantity+=$iteam->quantity;
                                                        $totalRate+=$iteam->price;
                                                        $totalDiscount+=$iteam->discount;
                                                        foreach($taxes as $taxe){
                                                            $taxDataPrice=App\Models\Utility::taxRate($taxe->rate,$iteam->price,$iteam->quantity);
                                                            if (array_key_exists($taxe->name,$taxesData))
                                                            {
                                                                $taxesData[$taxe->name] = $taxesData[$taxe->name]+$taxDataPrice;
                                                            }
                                                            else
                                                            {
                                                                $taxesData[$taxe->name] = $taxDataPrice;
                                                            }
                                                        }
                                                    ?>
                                                <?php endif; ?>
                                                <tr>
                                                    <td><?php echo e($key+1); ?></td>
                                                    <td><?php echo e(!empty($iteam->product())?$iteam->product()->name:''); ?></td>
                                                    <td><?php echo e($iteam->quantity); ?></td>
                                                    <td><?php echo e(\Auth::user()->priceFormat($iteam->price)); ?></td>
                                                    <td>
                                                        <?php if(!empty($iteam->tax)): ?>
                                                            <table>
                                                                <?php $totalTaxRate = 0;?>
                                                                <?php $__currentLoopData = $taxes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tax): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <?php
                                                                        $taxPrice=App\Models\Utility::taxRate($tax->rate,$iteam->price,$iteam->quantity);
                                                                        $totalTaxPrice+=$taxPrice;
                                                                    ?>
                                                                    <tr>
                                                                        <td class=""><?php echo e($tax->name .' ('.$tax->rate .'%)'); ?></td>
                                                                        <td><?php echo e(\Auth::user()->priceFormat($taxPrice)); ?></td>
                                                                    </tr>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </table>
                                                        <?php else: ?>
                                                            -
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                            <?php echo e(\Auth::user()->priceFormat($iteam->discount)); ?>


                                                    </td>
                                                    <td><?php echo e(!empty($iteam->description)?$iteam->description:'-'); ?></td>
                                                    <td class="text-end"><?php echo e(\Auth::user()->priceFormat(($iteam->price*$iteam->quantity))); ?></td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <tfoot>
                                            <tr>
                                                <td></td>
                                                <td><b><?php echo e(__('Total')); ?></b></td>
                                                <td><b><?php echo e($totalQuantity); ?></b></td>
                                                <td><b><?php echo e(\Auth::user()->priceFormat($totalRate)); ?></b></td>
                                                <td><b><?php echo e(\Auth::user()->priceFormat($totalTaxPrice)); ?></b></td>
                                                <td>
                                                        <b><?php echo e(\Auth::user()->priceFormat($totalDiscount)); ?></b>

                                                </td>

                                            </tr>
                                            <tr>
                                                <td colspan="6"></td>
                                                <td class="text-end"><b><?php echo e(__('Sub Total')); ?></b></td>
                                                <td class="text-end"><?php echo e(\Auth::user()->priceFormat($purchase->getSubTotal())); ?></td>
                                            </tr>

                                                <tr>
                                                    <td colspan="6"></td>
                                                    <td class="text-end"><b><?php echo e(__('Discount')); ?></b></td>
                                                    <td class="text-end"><?php echo e(\Auth::user()->priceFormat($purchase->getTotalDiscount())); ?></td>
                                                </tr>

                                            <?php if(!empty($taxesData)): ?>
                                                <?php $__currentLoopData = $taxesData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $taxName => $taxPrice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td colspan="6"></td>
                                                        <td class="text-end"><b><?php echo e($taxName); ?></b></td>
                                                        <td class="text-end"><?php echo e(\Auth::user()->priceFormat($taxPrice)); ?></td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                            <tr>
                                                <td colspan="6"></td>
                                                <td class="blue-text text-end"><b><?php echo e(__('Total')); ?></b></td>
                                                <td class="blue-text text-end"><?php echo e(\Auth::user()->priceFormat($purchase->getTotal())); ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="6"></td>
                                                <td class="text-end"><b><?php echo e(__('Paid')); ?></b></td>
                                                <td class="text-end"><?php echo e(\Auth::user()->priceFormat(($purchase->getTotal()-$purchase->getDue()))); ?></td>
                                            </tr>





                                            <tr>
                                                <td colspan="6"></td>
                                                <td class="text-end"><b><?php echo e(__('Due')); ?></b></td>
                                                <td class="text-end"><?php echo e(\Auth::user()->priceFormat($purchase->getDue())); ?></td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/imontechnologies/resources/views/purchase/view.blade.php ENDPATH**/ ?>