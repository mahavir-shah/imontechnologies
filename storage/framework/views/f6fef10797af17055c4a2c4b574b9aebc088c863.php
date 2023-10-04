<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage Purchase')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('purchase.index')); ?>"><?php echo e(__('Purchase')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Purchase Return Record')); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('css-page'); ?>
    <style>
        .list-group-bg{
            background-color : #e9efef99;
        }
        .list-active {
            background-color : #bfc8e799 !important;
        }
        .list-group-flush:first-of-type .list-group-bg{
            background-color : #bfc8e799;
        }
        .justifyContent{
            justify-content: space-between;
        }
        .left-menu span{
            font-weight: 700;
        }
        .list-group-item p{
            color: grey;
            margin-bottom: 0 !important;
        }
    </style>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('script-page'); ?>
    <script>
        function returnRecordFilter(e, id, purchase_id) {
            var goodId = id;
            var purchase_id = purchase_id;
            $.ajax({
                url: '<?php echo e(route('purchase.returnFilter')); ?>',
                type: 'get',
                data: {
                    'good_id': goodId,
                    'purchase_id' : purchase_id
                },
                cache: false,
                success: function (data) {
                    $('.list-group-flush:first-of-type .list-group-bg').css("background-color","#e9efef99");
                    $('.list-active').removeClass('list-active');
                    $(e).addClass('list-active');
                   
                    $('.goodsRecord').empty();
                    data.goods.map(item => {
                        var html = 
                        `<tr>
                            <td>${item.name}</td>
                            <td>${item.description ? item.description : '-'}</td>
                            <td>${item.price}</td>
                            <td>${item.required_qty}</td>
                            <td>${item.return_qty}</td>
                         </tr>
                        `;
                        $('.goodsRecord').append(html);
                    });
                }
            });
        }
    </script>
<?php $__env->stopPush(); ?>    
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Return Date</label>
                                        <input type="text" class="form-control" value="<?php echo e($getReturnRecord[0]['return_date']); ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" id="vender-box">
                                        <label class="form-label">Vendor</label>
                                        <input type="text" class="form-control" value="<?php echo e($vender['1']); ?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Warehouse</label>
                                        <input type="text" class="form-control" value="<?php echo e($warehouse['1']); ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Purchase Number</label>
                                        <input type="text" class="form-control" value="<?php echo e($purchase_number); ?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row">
                <div class="col-3 list-goods">
                    <input type="hidden" name="_token" id="token" value="<?php echo e(csrf_token()); ?>">
                    <?php $__currentLoopData = $getPurReturnId; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $goods): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="list-group list-group-flush my-2" id="useradd-sidenav">
                            <a href="#" onclick="returnRecordFilter(this,'<?php echo e($goods->return_id); ?>','<?php echo e($goods->purchase_id); ?>')" class="list-group-item list-group-item-action border-0 d-flex justifyContent list-group-bg"> 
                                <div class="left-menu">
                                    <span><?php echo e($goods->return_id); ?></span>
                                    <p><?php echo e($goods->return_date); ?></p>
                                </div>       
                                <div class="right-menu">
                                    <span></span>
                                    <p><?php echo e($goods->totalReturnQty); ?> / 0 item(s)</p>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div class="col-9">
                    <div class="card">
                        <div class="card-body table-border-style">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th> <?php echo e(__('Purchase')); ?></th>
                                        <th> <?php echo e(__('Description')); ?></th>
                                        <th> <?php echo e(__('Price')); ?></th>
                                        <th><?php echo e(__('Required')); ?></th>
                                        <th> <?php echo e(__('Returned')); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody class="goodsRecord">
                                        <?php $__currentLoopData = $getReturnRecord; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $goods): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td> <?php echo e($goods->name); ?> </td>
                                                <td> <?php echo e($goods->description  ? $goods->description  : '-'); ?> </td>
                                                <td> <?php echo e($goods->price); ?> </td>
                                                <td> <?php echo e($goods->required_qty); ?> </td>
                                                <td> <?php echo e($goods->return_qty); ?> </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/earthdlp/admin.imontechnologies.in/resources/views/purchase/purchase_return_record.blade.php ENDPATH**/ ?>