<?php if(!empty($sales) && count($sales) > 0): ?>

    <div class="container">
        <div class="row align-items-center mb-4 invoice mt-2">
            <div class="col invoice-details">
                <h1 class="invoice-id h6"><?php echo e($details['pos_id']); ?></h1>
                <div class="date"><b><?php echo e(__('Date')); ?>: </b><?php echo e($details['date']); ?></div>
            </div>
            <div class="col d-flex justify-content-end">
                

            </div>
        </div>
        <div class="row invoice mt-2">
            <div class="col contacts d-flex justify-content-between pb-4">
                <div class="invoice-to">
                    <div class="text-dark h6"><b><?php echo e(__('Billed To :')); ?></b></div>
                    <?php echo $details['customer']['details']; ?>

                </div>
                <?php if(!empty( $details['customer']['shippdetails'])): ?>
                <div class="invoice-to">
                    <div class="text-dark h6"><b><?php echo e(__('Shipped To :')); ?></div>
                    <?php echo $details['customer']['shippdetails']; ?>

                </div>
                <?php endif; ?>
                <div class="company-details">
                    <div class="text-dark h6"><b><?php echo e(__('From:')); ?></b></div>
                    <?php echo $details['user']['details']; ?>

                </div>
            </div>
        </div>
        <div class="col-12 col-md-12">
                <div class="invoice-table">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-left"><?php echo e(__('Items')); ?></th>
                                <th><?php echo e(__('Quantity')); ?></th>
                                <th class="text-right"><?php echo e(__('Price')); ?></th>
                                <th class="text-right"><?php echo e(__('Price Total')); ?></th>
                                <th class="text-right"><?php echo e(__('Tax')); ?></th>
                                <th class="text-right"><?php echo e(__('Tax Amount')); ?></th>
                                <th class="text-right"><?php echo e(__('Total')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $sales['data']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="cart-summary-table text-left">
                                        <?php echo e($value['name']); ?>

                                    </td>
                                    <td class="cart-summary-table">
                                        <?php echo e($value['quantity']); ?>

                                    </td>
                                    <td class="text-right cart-summary-table">
                                        <?php echo e($value['price']); ?>

                                    </td>
                                    <td class="text-right cart-summary-table">
                                        <?php echo e($value['priceTotal']); ?>

                                    </td>
                                    <td class="text-right cart-summary-table">
                                        <?php echo e($value['tax']); ?>

                                    </td>
                                    <td class="text-right cart-summary-table">
                                        <?php echo e($value['tax_amount']); ?>

                                    </td>
                                    <td class="text-right cart-summary-table">
                                        <?php echo e($value['subtotal']); ?>

                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class=""><?php echo e(__('Sub Total')); ?></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-right"><?php echo e($sales['sub_total']); ?></td>
                            </tr>
                            <tr>
                                <td class=""><?php echo e(__('Discount')); ?></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-right"><?php echo e($sales['discount']); ?></td>
                            </tr>
                            <tr class="pos-header">
                                <td class=""><?php echo e(__('Total')); ?></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-right"><?php echo e($sales['total']); ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <?php if($details['pay'] == 'show'): ?>
                    
                        <a href="#" class="btn btn-success btn-done-payment rounded mb-3 float-right"
                        data-url="<?php echo e(route('pos.data.store')); ?>"><?php echo e(__('PLACE ORDER')); ?></a>

                <?php endif; ?>
            </div>

    </div>

<?php endif; ?>
<?php /**PATH /home1/earthdlp/admin.imontechnologies.in/resources/views/pos/show.blade.php ENDPATH**/ ?>