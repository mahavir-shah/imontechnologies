<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Purchase Edit')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('purchase.index')); ?>"><?php echo e(__('Purchase')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Purchase Edit')); ?></li>
<?php $__env->stopSection(); ?>
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
                            subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).attr('rel'));
                        }
                        $('.subTotal').html(formatMoney(subTotal.toFixed(2)));
                        $('.totalAmount').html(formatMoney(subTotal.toFixed(2)));
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
        
        $(document).on('change', '#vender', function () {
            $('#vender_detail').removeClass('d-none');
            $('#vender_detail').addClass('d-block');
            $('#vender-box').removeClass('d-block');
            $('#vender-box').addClass('d-none');
            var id = $(this).val();
            var url = $(this).data('url');
            $.ajax({
                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': jQuery('#token').val()
                },
                data: {
                    'id': id
                },
                cache: false,
                success: function (data) {
                    if (data != '') {
                        $('#vender_detail').html(data);
                    } else {
                        $('#vender-box').removeClass('d-none');
                        $('#vender-box').addClass('d-block');
                        $('#vender_detail').removeClass('d-block');
                        $('#vender_detail').addClass('d-none');
                    }
                },
            });
        });

        $(document).on('click', '#remove', function () {
            $('#vender-box').removeClass('d-none');
            $('#vender-box').addClass('d-block');
            $('#vender_detail').removeClass('d-block');
            $('#vender_detail').addClass('d-none');
        });

        function formatMoney(number, decPlaces, decSep, thouSep) {
            decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
            decSep = typeof decSep === "undefined" ? "." : decSep;
            thouSep = typeof thouSep === "undefined" ? "," : thouSep;
            var sign = number < 0 ? "-" : "";
            var i = String(parseInt(number = Math.abs(Number(number) || 0).toFixed(decPlaces)));
            var j = (j = i.length) > 3 ? j % 3 : 0;

            return sign +
                (j ? i.substr(0, j) + thouSep : "") +
                i.substr(j).replace(/(\decSep{3})(?=\decSep)/g, "$1" + thouSep) +
                (decPlaces ? decSep + Math.abs(number - i).toFixed(decPlaces).slice(2) : "");
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
                            purchaseItems = data?.data != undefined ? JSON.parse(data.data) : null;
                            var amount = 0;
                            if (purchaseItems != null) {
                                amount = (purchaseItems.price * purchaseItems.quantity);
                                $(el).parent().parent().parent().find('.quantity').val(purchaseItems.quantity);
                                $(el).parent().parent().parent().find('.price').val(purchaseItems.price);
                                $(el).parent().parent().parent().find('.discount').val(purchaseItems.discount);
                                $(el).parents('tbody').find('.description').val(purchaseItems.description);
                            } else {
                                $(el).parent().parent().parent().find('.quantity').val(1);
                                $(el).parent().parent().parent().find('.price').val(item.product.purchase_price);
                                $(el).parent().parent().parent().find('.discount').val(0);
                                $(el).parents('tbody').find('.description').val(item.product.description);
                            }
                            var taxes = '';
                            var tax = [];

                            var totalItemTaxRate = 0;
                            if (item.taxes == 0) {
                                taxes += '-';
                            } else {
                                for (var i = 0; i < item.taxes.length; i++) {
                                    taxes += '<span class="badge bg-primary p-2 px-3 rounded mt-1 mr-1">' + item.taxes[i].name + ' ' + '(' + item.taxes[i].rate + '%)' + '</span>';
                                    tax.push(item.taxes[i].id);
                                    totalItemTaxRate += parseFloat(item.taxes[i].rate);
                                }
                            }
                            if (purchaseItems != null) {
                                var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (purchaseItems.price * purchaseItems.quantity));
                            } else {
                                var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (item.product.purchase_price * 1));
                            }



                            // $(el.parent().parent().find('.itemTaxPrice')).val(itemTaxPrice.toFixed(2));
                            // $(el.parent().parent().find('.itemTaxRate')).val(totalItemTaxRate.toFixed(2));
                            // $(el.parent().parent().find('.taxes')).html(taxes);
                            $('.itemTaxPrice').val(itemTaxPrice.toFixed(2));
                            $('.itemTaxRate').val(totalItemTaxRate.toFixed(2));
                            $('.taxes').html(taxes);
                            $(el.parent().parent().find('.tax')).val(tax);
                            $(el.parent().parent().find('.unit')).html(item.unit);


                            if (purchaseItems != null) {
                                // $(el.parent().parent().find('.amount')).html(amount);
                                $(el).parent().parent().parent().find('.amount').attr("rel",amount);
                                $(el).parent().parent().parent().find('.amount').html(formatMoney(amount));
                            } else {
                                // $(el.parent().parent().find('.amount')).html(item.totalAmount);
                                $(el).parent().parent().parent().find('.amount').attr("rel",amount);
                                $(el).parent().parent().parent().find('.amount').html(formatMoney(item.totalAmount));
                            }

                            var inputs = $(".amount");
                            var subTotal = 0;
                            for (var i = 0; i < inputs.length; i++) {
                                subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).attr('rel'));
                            }
                            $('.subTotal').html(subTotal.toFixed(2));

                            var totalItemDiscountPrice = 0;
                            var itemDiscountPriceInput = $('.discount');      
 
                            for (var k = 0; k < itemDiscountPriceInput.length; k++) {

                                totalItemDiscountPrice += parseFloat(itemDiscountPriceInput[k].value);
                            }


                            var totalItemPrice = 0;
                            var priceInput = $('.price');
                            for (var j = 0; j < priceInput.length; j++) {
                                totalItemPrice += parseFloat(priceInput[j].value);
                            }

                            var totalItemTaxPrice = 0;
                            var itemTaxPriceInput = $('.itemTaxPrice');
                            for (var j = 0; j < itemTaxPriceInput.length; j++) {
                                totalItemTaxPrice += parseFloat(itemTaxPriceInput[j].value);
                            }
                            console.log(itemTaxPriceInput[0]);

                            $('.totalTax').html(formatMoney(totalItemTaxPrice.toFixed(2)));
                            $('.totalAmount').html(formatMoney((parseFloat(subTotal) - parseFloat(totalItemDiscountPrice) + parseFloat(totalItemTaxPrice)).toFixed(2)));
                            $('.totalDiscount').html(formatMoney(totalItemDiscountPrice.toFixed(2)));
                        }
                    });

                },
            });
        }
        $(document).on('change', '.item', function () {
            changeItem($(this));
        });

        $(document).on('keyup', '.quantity', function () {
            var quntityTotalTaxPrice = 0;

            var el = $(this).parent().parent().parent().parent();
            var quantity = $(this).val();
            var price = $(el.find('.price')).val();
            var discount = $(el.find('.discount')).val();

            var totalItemPrice = (quantity * price);
            var amount = (totalItemPrice);
            $(el.find('.amount')).html(amount);
            $(el.find('.amount')).html(formatMoney(amount));
            $(el.find('.amount')).attr('rel',amount.toFixed(2));

            var totalItemTaxRate = $(el.find('.itemTaxRate')).val();
            var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (totalItemPrice));
            $(el.find('.itemTaxPrice')).val(itemTaxPrice.toFixed(2));


            var totalItemTaxPrice = 0;
            var itemTaxPriceInput = $('.itemTaxPrice');
            for (var j = 0; j < itemTaxPriceInput.length; j++) {
                totalItemTaxPrice += parseFloat(itemTaxPriceInput[j].value);
            }


            var inputs = $(".amount");
            var subTotal = 0;
            for (var i = 0; i < inputs.length; i++) {
                subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).attr('rel'));
            }
            $('.subTotal').html(formatMoney(subTotal.toFixed(2)));
            $('.totalTax').html(formatMoney(totalItemTaxPrice.toFixed(2)));

            $('.totalAmount').html(formatMoney((parseFloat(subTotal) + parseFloat(totalItemTaxPrice)).toFixed(2)));

        })

        $(document).on('keyup', '.price', function () {
            var el = $(this).parent().parent().parent().parent();
            var price = $(this).val();
            var quantity = $(el.find('.quantity')).val();
            var discount = $(el.find('.discount')).val();
            var totalItemPrice = (quantity * price);

            var amount = (totalItemPrice);
            $(el.find('.amount')).html(amount);
            $(el.find('.amount')).html(formatMoney(amount));
            $(el.find('.amount')).attr('rel',amount.toFixed(2));


            var totalItemTaxRate = $(el.find('.itemTaxRate')).val();
            var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (totalItemPrice));
            $(el.find('.itemTaxPrice')).val(itemTaxPrice.toFixed(2));


            var totalItemTaxPrice = 0;
            var itemTaxPriceInput = $('.itemTaxPrice');
            for (var j = 0; j < itemTaxPriceInput.length; j++) {
                totalItemTaxPrice += parseFloat(itemTaxPriceInput[j].value);
            }


            var inputs = $(".amount");
            var subTotal = 0;
            for (var i = 0; i < inputs.length; i++) {
                subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).attr('rel'));
            }
            $('.totalTax').html(formatMoney(totalItemTaxPrice.toFixed(2)));

            $('.subTotal').html(formatMoney(subTotal.toFixed(2)));
            $('.totalAmount').html(formatMoney((parseFloat(subTotal) + parseFloat(totalItemTaxPrice)).toFixed(2)));

        })


        $(document).on('keyup', '.discount', function () {
            var el = $(this).parent().parent().parent().parent();
            var discount = $(this).val();
            var price = $(el.find('.price')).val();

            var quantity = $(el.find('.quantity')).val();
            var totalItemPrice = (quantity * price);

            var totalItemTaxRate = $(el.find('.itemTaxRate')).val();
            var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (totalItemPrice));
            $(el.find('.itemTaxPrice')).val(itemTaxPrice.toFixed(2));


            var totalItemTaxPrice = 0;
            var itemTaxPriceInput = $('.itemTaxPrice');
            for (var j = 0; j < itemTaxPriceInput.length; j++) {
                totalItemTaxPrice += parseFloat(itemTaxPriceInput[j].value);
            }


            var totalItemDiscountPrice = 0;
            var itemDiscountPriceInput = $('.discount');

            for (var k = 0; k < itemDiscountPriceInput.length; k++) {

                totalItemDiscountPrice += itemDiscountPriceInput[k].value != "" ? parseFloat(itemDiscountPriceInput[k].value) : 0;
            }

            var amount = (totalItemPrice);
            $(el.find('.amount')).html(amount);
            $(el.find('.amount')).html(formatMoney(amount));
            $(el.find('.amount')).attr('rel',amount.toFixed(2));

            var inputs = $(".amount");
            var subTotal = 0;
            for (var i = 0; i < inputs.length; i++) {
                subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).attr('rel'));
            }
            $('.subTotal').html(formatMoney(subTotal.toFixed(2)));
            $('.totalDiscount').html(formatMoney(totalItemDiscountPrice.toFixed(2)));
            $('.totalTax').html(formatMoney(totalItemTaxPrice.toFixed(2)));

            $('.totalAmount').html(formatMoney((parseFloat(subTotal) - parseFloat(totalItemDiscountPrice) + parseFloat(totalItemTaxPrice)).toFixed(2)));
        })

        $(document).on('click', '[data-repeater-create]', function () {
            $('.item :selected').each(function () {
                var id = $(this).val();
                $(".item option[value=" + id + "]").prop("disabled", true);
            });
        })

        $(document).on('click', '[data-repeater-delete]', function () {
            // $('.delete_item').click(function () {
            if (confirm('Are you sure you want to delete this element?')) {
                var el = $(this).parent().parent();
                var id = $(el.find('.id')).val();

                $.ajax({
                    url: '<?php echo e(route('purchase.product.destroy')); ?>',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': jQuery('#token').val()
                    },
                    data: {
                        'id': id
                    },
                    cache: false,
                    success: function (data) {

                    },
                });

            }
        });
    </script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">

        <?php echo e(Form::model($purchase, array('route' => array('purchase.update', $purchase->id), 'method' => 'PUT','class'=>'w-100'))); ?>

        <div class="col-12">
            <input type="hidden" name="_token" id="token" value="<?php echo e(csrf_token()); ?>">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" id="vender-box">
                                <?php echo e(Form::label('vender_id', __('Vendor'),['class'=>'form-label'])); ?>

                                <?php echo e(Form::select('vender_id', $venders,null, array('class' => 'form-control select','id'=>'vender','data-url'=>route('purchase.vender'),'required'=>'required'))); ?>

                            </div>
                            <div id="vender_detail" class="d-none">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php echo e(Form::label('warehouse_id', __('Warehouse'),['class'=>'form-label'])); ?>

                                        <?php echo e(Form::select('warehouse_id', $warehouse,null, array('class' => 'form-control select','required'=>'required'))); ?>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php echo e(Form::label('purchase_number', __('Purchase Number'),['class'=>'form-label'])); ?>

                                        <input type="text" class="form-control" value="<?php echo e($purchase_number); ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php echo e(Form::label('purchase_date', __('Purchase Date'),['class'=>'form-label'])); ?>

                                        <?php echo e(Form::date('purchase_date',null,array('class'=>'form-control','required'=>'required'))); ?>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php echo e(Form::label('due_date', __('Due Date'),['class'=>'form-label'])); ?>

                                        <?php echo e(Form::date('due_date',date('Y-m-d'),array('class'=>'form-control','required'=>'required'))); ?>

                                    </div>
                                </div>
                            </div>




                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <h5 class="d-inline-block mb-4"><?php echo e(__('Product & Services')); ?></h5>
            <div class="card repeater" data-value='<?php echo json_encode($purchase->items); ?>'>
                <div class="item-section py-2">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-md-12 d-flex align-items-center justify-content-between justify-content-md-end">
                            <div class="all-button-box me-2">
                                <a href="#" data-repeater-create="" class="btn btn-primary" data-bs-toggle="modal" data-target="#add-bank">
                                    <i class="ti ti-plus"></i> <?php echo e(__('Add item')); ?>

                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-border-style ">
                    <div class="table-responsive">
                        <table class="table  mb-0" data-repeater-list="items" id="sortable-table">
                            <thead>
                            <tr>
                                <th><?php echo e(__('Items')); ?></th>
                                <th><?php echo e(__('Quantity')); ?></th>
                                <th><?php echo e(__('Price')); ?> </th>
                                <th><?php echo e(__('Tax')); ?> (%)</th>
                                <th><?php echo e(__('Discount')); ?></th>
                                <th class="text-end"><?php echo e(__('Amount')); ?> <br><small class="text-danger font-weight-bold"><?php echo e(__('before tax & discount')); ?></small></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody class="ui-sortable" data-repeater-item>
                            <tr>
                                <?php echo e(Form::hidden('id',null, array('class' => 'form-control id'))); ?>

                                <td width="25%">
                                    <div class="form-group">
                                        <?php echo e(Form::select('item', $product_services,null, array('class' => 'form-control select item','data-url'=>route('purchase.product')))); ?>

                                    </div>
                                </td>
                                <td>
                                    <div class="form-group price-input input-group search-form">
                                        <?php echo e(Form::text('quantity',null, array('class' => 'form-control quantity','required'=>'required','placeholder'=>__('Qty'),'required'=>'required'))); ?>

                                        <span class="unit input-group-text bg-transparent"></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group price-input input-group search-form">
                                        <?php echo e(Form::text('price',null, array('class' => 'form-control price','required'=>'required','placeholder'=>__('Price'),'required'=>'required'))); ?>

                                        <span class="input-group-text bg-transparent"><?php echo e(\Auth::user()->currencySymbol()); ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="taxes"></div>
                                            <?php echo e(Form::hidden('tax','', array('class' => 'form-control tax'))); ?>

                                            <?php echo e(Form::hidden('itemTaxPrice','', array('class' => 'form-control itemTaxPrice'))); ?>

                                            <?php echo e(Form::hidden('itemTaxRate','', array('class' => 'form-control itemTaxRate'))); ?>

                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group price-input input-group search-form">
                                        <?php echo e(Form::text('discount',null, array('class' => 'form-control discount','required'=>'required','placeholder'=>__('Discount')))); ?>

                                        <span class="input-group-text bg-transparent"><?php echo e(\Auth::user()->currencySymbol()); ?></span>
                                    </div>
                                </td>
                                <td class="text-end amount">
                                    0.00
                                </td>

                                <td>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete proposal product')): ?>
                                        <a href="#" class="ti ti-trash text-white repeater-action-btn bg-danger ms-2 bs-pass-para" data-repeater-delete></a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="form-group">
                                        <?php echo e(Form::textarea('description', null, ['class'=>'description form-control','rows'=>'2','placeholder'=>__('Description')])); ?>

                                    </div>
                                </td>
                                <td colspan="5"></td>
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td></td>
                                <td><strong><?php echo e(__('Sub Total')); ?> (<?php echo e(\Auth::user()->currencySymbol()); ?>)</strong></td>
                                <td class="text-end subTotal">0.00</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td></td>
                                <td><strong><?php echo e(__('Discount')); ?> (<?php echo e(\Auth::user()->currencySymbol()); ?>)</strong></td>
                                <td class="text-end totalDiscount">0.00</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td></td>
                                <td><strong><?php echo e(__('Tax')); ?> (<?php echo e(\Auth::user()->currencySymbol()); ?>)</strong></td>
                                <td class="text-end totalTax">0.00</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td class="blue-text"><strong><?php echo e(__('Total Amount')); ?> (<?php echo e(\Auth::user()->currencySymbol()); ?>)</strong></td>
                                <td class="blue-text text-end totalAmount">0.00</td>
                                <td></td>
                            </tr>
                            </tfoot>
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


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\ds-store\resources\views/purchase/edit.blade.php ENDPATH**/ ?>