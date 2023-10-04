<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Goods Received')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('purchase.index')); ?>"><?php echo e(__('Purchase')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Goods Received')); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('css-page'); ?>
    <style>
        .text-blue {
            color : #3c5ee1 !important;
        }
        .text-green{
            color : #21c50ef8 !important;
        }
        .text-red{
            color : #f51010 !important;
        }
    </style>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('script-page'); ?>

    <script src="<?php echo e(asset('js/jquery-ui.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/jquery.repeater.min.js')); ?>"></script>
    <script>
        var selector = "body";
        if ($(selector + " .repeater").length) {
            var $dragAndDrop = $("body .repeater tbody").sortable({
                handle: '.sort-handler'
            });
            var $repeater = $(selector + ' .repeater').repeater({
                initEmpty: true,
                defaultValues: {
                    'status': 1
                },
                show: function () {
                    $(this).slideDown();
                    var file_uploads = $(this).find('input.multi');
                    if (file_uploads.length) {
                        $(this).find('input.multi').MultiFile({
                            max: 3,
                            accept: 'png|jpg|jpeg',
                            max_size: 2048
                        });
                    }
                    if($('.select2').length) {
                        $('.select2').select2();
                    }
                },
                hide: function (deleteElement) {

                    $(this).slideUp(deleteElement);
                    $(this).remove();
                    var inputs = $(".amount");
                    var subTotal = 0;
                    for (var i = 0; i < inputs.length; i++) {
                        subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
                    }
                    $('.subTotal').html(subTotal.toFixed(2));
                    $('.totalAmount').html(subTotal.toFixed(2));

                },
                ready: function (setIndexes) {
                    $dragAndDrop.on('drop', setIndexes);
                },
                isFirstItemUndeletable: true
            });
            var value = $(selector + " .repeater").attr('data-value');
            if (typeof value != 'undefined' && value.length != 0) {
                value = JSON.parse(value);
                $repeater.setList(value);
                for (var i = 0; i < value.length; i++) {
                    var tr = $('#sortable-table .id[value="' + value[i].id + '"]').parent();
                    tr.find('.item').val(value[i].product_id);
                    changeItem(tr.find('.item'));
                }
            }

        }

        var purchase_id = '<?php echo e($purchase->id); ?>';

        function changeItem(element) {
            var iteams_id = element.val();

            var url = element.data('url');
            var el = element;
            $.ajax({
                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': jQuery('#token').val()
                },
                data: {
                    'product_id': iteams_id
                },
                cache: false,
                success: function (data) {
                    var item = JSON.parse(data);

                    $.ajax({
                        url: '<?php echo e(route('purchase.items')); ?>',
                        type: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': jQuery('#token').val()
                        },
                        data: {
                            'purchase_id': purchase_id,
                            'product_id': iteams_id,
                        },
                        cache: false,
                        success: function (data) {
                            var purchaseItems = JSON.parse(data.data);
                            
                            data.goods.map((good) => {
                                if(good.id === purchaseItems.id){
                                    purchaseItems.totalQty = good.totalQty;
                                }
                            })
                            $(el.parent().parent().parent().find('.productId')).val(iteams_id);
                            if (purchaseItems != null) {
                                var amount = (purchaseItems.price * purchaseItems.quantity);
                                if(purchaseItems.totalQty != undefined){
                                    var qty = purchaseItems.quantity - purchaseItems.totalQty;
                                    $(el.parent().parent().parent().find('.requiredQty')).val(qty);
                                    $(el.parent().parent().parent().find('.receivingQty')).val(qty);
                                }else{
                                    $(el.parent().parent().parent().find('.requiredQty')).val(purchaseItems.quantity);
                                    $(el.parent().parent().parent().find('.receivingQty')).val(purchaseItems.quantity);
                                }
                                $(el.parent().parent().parent().find('.price')).val(purchaseItems.price);
                            } else {
                                $(el.parent().parent().parent().find('.quantity')).val(1);
                                $(el.parent().parent().parent().find('.price')).val(item.product.purchase_price);
                            }
                        }
                    });


                },
            });
        }
        $(document).on('change', '.item', function () {
            changeItem($(this));
        });

        $(document).on('click', '[data-repeater-create]', function () {
            $('.item :selected').each(function () {
                var id = $(this).val();
                $(".item option[value=" + id + "]").prop("disabled", true);
            });
        })
    </script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">

        <?php echo e(Form::model($purchase, array('route' => array('purchase.addGoodReceived', $purchase->id), 'method' => 'POST','class'=>'w-100'))); ?>

        <div class="col-12">
            <input type="hidden" name="_token" id="token" value="<?php echo e(csrf_token()); ?>">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php echo e(Form::label('received_date', __('Received Date'),['class'=>'form-label'])); ?>

                                        <input type="text" class="form-control" value="<?php echo e(date('Y-m-d')); ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" id="vender-box">
                                        <?php echo e(Form::label('vender_id', __('Vendor'),['class'=>'form-label'])); ?>

                                        <?php echo e(Form::select('vender_id', $venders,null, array('class' => 'form-control select','id'=>'vender','data-url'=>route('purchase.vender'),'required'=>'required','readonly'))); ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php echo e(Form::label('warehouse_id', __('Warehouse'),['class'=>'form-label'])); ?>

                                        <?php echo e(Form::select('warehouse_id', $warehouse,null, array('class' => 'form-control select','required'=>'required','readonly'))); ?>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php echo e(Form::label('purchase_number', __('Purchase Number'),['class'=>'form-label'])); ?>

                                        <input type="text" class="form-control" value="<?php echo e($purchase_number); ?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <h5 class="d-inline-block mb-4"><?php echo e(__('Received Goods of Purchase Order')); ?></h5>
            <div class="card repeater" data-value='<?php echo json_encode($purchase->items); ?>'>
                <div class="card-body table-border-style ">
                    <div class="table-responsive">
                        <table class="table  mb-0" data-repeater-list="items" id="sortable-table">
                            <thead>
                            <tr>
                                <th><?php echo e(__('Items')); ?></th>
                                <th><?php echo e(__('Price')); ?> </th>
                                <th><?php echo e(__('Discription')); ?></th>
                                <th><?php echo e(__('Required')); ?></th>
                                <th><?php echo e(__('Receiving')); ?></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody class="ui-sortable" data-repeater-item>
                                <tr>
                                    <?php echo e(Form::hidden('id',null, array('class' => 'form-control id'))); ?>

                                    <?php echo e(Form::hidden('required_qty',null, array('class' => 'form-control requiredQty'))); ?>

                                    <?php echo e(Form::hidden('product_id',null, array('class' => 'form-control productId '))); ?>

                                    <td width="25%">
                                        <div class="form-group">
                                            <?php echo e(Form::select('item', $product_services,null, array('class' => 'form-control select item','disabled','data-url'=>route('purchase.product')))); ?>

                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group price-input input-group search-form">
                                            <?php echo e(Form::text('price',null, array('class' => 'form-control price','disabled','placeholder'=>__('Price'),'required'=>'required'))); ?>

                                            <span class="input-group-text bg-transparent"><?php echo e(\Auth::user()->currencySymbol()); ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <?php echo e(Form::textarea('description', null, ['class'=>'form-control','disabled','rows'=>'1','placeholder'=>__('Description')])); ?>

                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group price-input input-group search-form">
                                            <?php echo e(Form::text('quantity',null, array('class' => 'form-control quantity requiredQty','disabled','placeholder'=>__('Qty'),'required'=>'required'))); ?>

                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group price-input input-group search-form">
                                            <?php echo e(Form::text('receiving_qty',null, array('class' => 'form-control quantity receivingQty','placeholder'=>__('Qty'),'required'=>'required'))); ?>

                                            <span class="input-group-text bg-transparent qtyValidate text-red">*</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <input type="button" value="<?php echo e(__('Cancel')); ?>" onclick="location.href = '<?php echo e(route("purchase.index")); ?>';" class="btn btn-light">
            <input type="submit" value="<?php echo e(__('Update')); ?>" class="btn btn-primary">
        </div>
        <?php echo e(Form::close()); ?>

    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/imontechnologies/resources/views/purchase/good_received.blade.php ENDPATH**/ ?>