<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Estimate Create')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('estimate.index')); ?>"><?php echo e(__('Estimate')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Estimate Create')); ?></li>
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
                initEmpty: false,
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
                    $('.select2').select2();
                },
                hide: function (deleteElement) {
                    if (confirm('Are you sure you want to delete this element?')) {
                        $(this).slideUp(deleteElement);
                        $(this).remove();

                        var inputs = $(".amount");
                        var subTotal = 0;
                        for (var i = 0; i < inputs.length; i++) {
                            subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
                        }
                        $('.subTotal').html(formatMoney(subTotal.toFixed(2)));
                        $('.totalAmount').html(formatMoney(subTotal.toFixed(2)));
                    }
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
            }

        }

        $(document).on('change', '#customer', function () {
            $('#customer_detail').removeClass('d-none');
            $('#customer_detail').addClass('d-block');
            $('#customer-box').removeClass('d-block');
            $('#customer-box').addClass('d-none');
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
                        $('#customer_detail').html(data);
                    } else {
                        $('#customer-box').removeClass('d-none');
                        $('#customer-box').addClass('d-block');
                        $('#customer_detail').removeClass('d-block');
                        $('#customer_detail').addClass('d-none');
                    }
                },

            });
        });

        $(document).on('click', '#remove', function () {
            $('#customer-box').removeClass('d-none');
            $('#customer-box').addClass('d-block');
            $('#customer_detail').removeClass('d-block');
            $('#customer_detail').addClass('d-none');
        })

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

        $(document).on('change', '.item', function () {
            var iteams_id = $(this).val();
            var url = $(this).data('url');
            var el = $(this);
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
                    $(el.parent().parent().find('.quantity')).val(1);
                    var price = 0;
					if($('select[name="customer_id"] option:selected').attr('rel') == "Alliance"){
						$(el.parent().parent().find('.price')).val(item.product_price.alliance);
                        price = item.product_price.alliance; 
					}
					if($('select[name="customer_id"] option:selected').attr('rel') == "Premium"){
						$(el.parent().parent().find('.price')).val(item.product_price.premium);
                        price = item.product_price.premium; 
					}
					if($('select[name="customer_id"] option:selected').attr('rel') == "Standard"){
						$(el.parent().parent().find('.price')).val(item.product_price.standard);
                        price = item.product_price.standard; 
					}
					$(el.parents('tbody').find('textarea[placeholder="Description"]')).val(item.product.description);
					let prefixname = $(el.parents('tbody').find('textarea[placeholder="Description"]')).attr('name').split('[description]')[0];
					console.log("prefixname",prefixname);
					$(el.parents('tbody').find('p.hsn_code')).html(
						`<span>HSN Code: `+item.product.hsn_code+`</span>`+
						`<input type="hidden" name="`+prefixname+`[hsn_code]" value="`+item.product.hsn_code+`"/>`
					);   

                    var taxes = '';
                    var tax = [];

                    var totalItemTaxRate = 0;
                    if (item.taxes == 0) {
                        taxes += '-';
                    } else {
                        for (var i = 0; i < item.taxes.length; i++) {
                            taxes += '<span class="badge  badge bg-primary mt-1 mr-2">' + item.taxes[i].name + ' ' + '(' + item.taxes[i].rate + '%)' + '</span>';
                            tax.push(item.taxes[i].id);
                            totalItemTaxRate += parseFloat(item.taxes[i].rate);
                        }
                    }
                    var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (price * 1));

                    $(el.parent().parent().find('.itemTaxPrice')).val(itemTaxPrice.toFixed(2));
                    $(el.parent().parent().find('.itemTaxRate')).val(totalItemTaxRate.toFixed(2));
                    $(el.parent().parent().find('.taxes')).html(taxes);
                    $(el.parent().parent().find('.tax')).val(tax);
                    $(el.parent().parent().find('.unit')).html(item.unit);
                    $(el.parent().parent().find('.discount')).val(0);
                    $(el.parent().parent().find('.amount')).html(formatMoney(item.totalAmount));
                    $(el.parent().parent().find('.amount')).attr('rel',item.totalAmount.toFixed(2));



                    var inputs = $(".amount");
                    var subTotal = 0;
                    for (var i = 0; i < inputs.length; i++) {
                        subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).attr('rel'));
                    }
                    $('.subTotal').html(formatMoney(subTotal.toFixed(2)));


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

                    $('.totalTax').html(formatMoney(totalItemTaxPrice.toFixed(2)));
                    $('.totalAmount').html(formatMoney((parseFloat(subTotal) + parseFloat(totalItemTaxPrice)).toFixed(2)));

                },
            });
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

                totalItemDiscountPrice += parseFloat(itemDiscountPriceInput[k].value);
            }

            var amount = (totalItemPrice);
            $(el.find('.amount')).html(amount);
            $(el.find('.amount')).html(formatMoney(amount));
            $(el.find('.amount')).attr('rel',amount.toFixed(2));

            var inputs = $(".amount");
            var subTotal = 0;
            for (var i = 0; i < inputs.length; i++) {
                subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
            }
            $('.subTotal').html(subTotal.toFixed(2));
            $('.totalDiscount').html(totalItemDiscountPrice.toFixed(2));
            $('.totalTax').html(totalItemTaxPrice.toFixed(2));

            $('.totalAmount').html((parseFloat(subTotal) - parseFloat(totalItemDiscountPrice) + parseFloat(totalItemTaxPrice)).toFixed(2));
        })

        var customerId = '<?php echo e($customerId); ?>';
        if (customerId > 0) {
            $('#customer').val(customerId).change();
        }

    </script>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <?php echo e(Form::open(array('url' => 'estimate','class'=>'w-100'))); ?>

        <div class="col-12">
            <input type="hidden" name="_token" id="token" value="<?php echo e(csrf_token()); ?>">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" id="customer-box">
                                <?php echo e(Form::label('customer_id', __('Customer'),['class'=>'form-label'])); ?>

                                
                                <select name="customer_id" class="form-control select" id="customer" data-url="<?php echo e(route('proposal.customer')); ?>" required>
                                    <option value="">Select Customer</option>
                                    <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($data->id); ?>" rel="<?php echo e($data->dealer_category); ?>"><?php echo e($data->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                  <option>  
                                </select>
                            </div>

                            <div id="customer_detail" class="d-none">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php echo e(Form::label('issue_date', __('Estimate Date'),['class'=>'form-label'])); ?>

                                        <div class="form-icon-user">
                                            <?php echo e(Form::date('issue_date',date('Y-m-d'),array('class'=>'form-control','required'=>'required'))); ?>

                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php echo e(Form::label('proposal_number', __('Estimate Number'),['class'=>'form-label'])); ?>

                                        <div class="form-icon-user">
                                            <input type="text" class="form-control" value="<?php echo e($proposal_number); ?>" readonly>
                                        </div>
                                    </div>
                                </div>















                                <?php if(!$customFields->isEmpty()): ?>
                                    <div class="col-md-6">
                                        <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                                            <?php echo $__env->make('customFields.formBuilder', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <h5 class="d-inline-block mb-4"><?php echo e(__('Product & Services')); ?></h5>
            <div class="card repeater">
                <div class="item-section py-2">
                    <div class="d-flex align-items-center float-end me-2">
                        <a href="#" data-repeater-create="" class="btn btn-primary mb-2" data-bs-toggle="modal" data-target="#add-bank">
                            <i class="ti ti-plus"></i> <?php echo e(__('Add item')); ?>

                        </a>
                    </div>

                    <div class="card-body mt-3">
                    <div class="table-responsive">
                        <table class="table mb-0" data-repeater-list="items">
                            <thead>
                            <tr>
                                <th><?php echo e(__('Items')); ?></th>
                                <th><?php echo e(__('Quantity')); ?></th>
                                <th><?php echo e(__('Price')); ?> </th>
                                <th><?php echo e(__('Tax')); ?> (%)</th>
                                <th><?php echo e(__('Discount')); ?></th>
                                <th class="text-end"><?php echo e(__('Amount')); ?> <br>
                                    <small class="text-danger font-weight-bold"><?php echo e(__('before tax & discount')); ?></small>
                                </th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody class="ui-sortable" data-repeater-item>
                            <tr>
                                <td width="25%" class="form-group pt-0">
                                    <?php echo e(Form::select('item', $product_services,'', array('class' => 'form-control item'  ,'data-url'=>route('proposal.product'),'required'=>'required'))); ?>

                                </td>
                                <td>
                                    <div class="form-group price-input input-group search-form">
                                        <?php echo e(Form::text('quantity','', array('class' => 'form-control quantity','required'=>'required','placeholder'=>__('Qty'),'required'=>'required'))); ?>

                                        <span class="unit input-group-text bg-transparent"></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group price-input input-group search-form">
                                        <?php echo e(Form::text('price','', array('class' => 'form-control price','required'=>'required','placeholder'=>__('Price'),'required'=>'required'))); ?>

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
                                        <?php echo e(Form::text('discount','', array('class' => 'form-control discount','required'=>'required','placeholder'=>__('Discount')))); ?>

                                        <span class="input-group-text bg-transparent"><?php echo e(\Auth::user()->currencySymbol()); ?></span>
                                    </div>
                                </td>
                                <td class="text-end amount">
                                    0.00
                                </td>
                                <td>
                                    <a href="#" class="ti ti-trash text-danger" data-repeater-delete></a>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="form-group">
                                        <?php echo e(Form::textarea('description', null, ['class'=>'form-control','rows'=>'1','placeholder'=>__('Description')])); ?>

										<p class="text-success hsn_code"></p>
                                    </div>
                                </td>
                                <td colspan="5"></td>
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr class="border-none">
                                <td><?php echo e(Form::label('customer_note',__('Customer Notes'))); ?></td>
                                <td></td>
                                <td>&nbsp;</td>
                                <td></td>
                                <td><strong><?php echo e(__('Sub Total')); ?> (<?php echo e(\Auth::user()->currencySymbol()); ?>)</strong></td>
                                <td class="text-end subTotal">0.00</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><?php echo e(Form::textarea('customer_note', null, ['class'=>'form-control','rows'=>'1','placeholder'=>__('Customer Note')])); ?></td>
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
                                <td class="blue-text border-none"><strong><?php echo e(__('Total Amount')); ?> (<?php echo e(\Auth::user()->currencySymbol()); ?>)</strong></td>
                                <td class="text-end totalAmount blue-text border-none"></td>
                                <td></td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                </div>
            </div>

            <div class="modal-footer">
                <input type="button" value="<?php echo e(__('Cancel')); ?>" onclick="location.href = '<?php echo e(route("estimate.index")); ?>';" class="btn btn-light">
                <input type="submit" value="<?php echo e(__('Create')); ?>" class="btn  btn-primary">
            </div>
            <?php echo e(Form::close()); ?>

        </div>
    </div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/imontechnologies/resources/views/proposal/create.blade.php ENDPATH**/ ?>