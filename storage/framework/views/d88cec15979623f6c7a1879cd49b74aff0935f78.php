   <?php
    $settings_data = \App\Models\Utility::settingsById($bill->created_by);
?>
    <!DOCTYPE html>
<html lang="en" dir="<?php echo e($settings_data['SITE_RTL'] == 'on'?'rtl':''); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Lato&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">

    <style type="text/css">.resize-observer[data-v-b329ee4c] {
            position: absolute;
            top: 0;
            left: 0;
            z-index: -1;
            width: 100%;
            height: 100%;
            border: none;
            background-color: transparent;
            pointer-events: none;
            display: block;
            overflow: hidden;
            opacity: 0
        }

        .resize-observer[data-v-b329ee4c] object {
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            overflow: hidden;
            pointer-events: none;
            z-index: -1
        }</style>
    <style type="text/css">p[data-v-f2a183a6] {
            line-height: 1.2em;
            margin: 0 0 2px 0;
        }

        pre[data-v-f2a183a6] {
            margin: 0;
        }

        .d-table[data-v-f2a183a6] {
            margin-top: 20px;
        }

        .d-table-footer[data-v-f2a183a6] {
            display: -webkit-box;
            display: flex;
        }

        .d-table-controls[data-v-f2a183a6] {
            -webkit-box-flex: 2;
            flex: 2;
        }

        .d-table-summary[data-v-f2a183a6] {
            -webkit-box-flex: 1;
            flex: 1;
        }

        .d-table-summary-item[data-v-f2a183a6] {
            width: 100%;
            display: -webkit-box;
            display: flex;
        }

        .d-table-label[data-v-f2a183a6] {
            -webkit-box-flex: 1;
            flex: 1;
            display: -webkit-box;
            display: flex;
            -webkit-box-pack: end;
            justify-content: flex-end;
            padding-top: 9px;
            padding-bottom: 9px;
        }

        .d-table-label .form-input[data-v-f2a183a6] {
            margin-left: 10px;
            width: 80px;
            height: 24px;
        }

        .d-table-label .form-input-mask-text[data-v-f2a183a6] {
            top: 3px;
        }

        .d-table-value[data-v-f2a183a6] {
            -webkit-box-flex: 1;
            flex: 1;
            text-align: right;
            padding-top: 9px;
            padding-bottom: 9px;
            padding-right: 10px;
        }

        .d-table-spacer[data-v-f2a183a6] {
            margin-top: 5px;
        }

        .d-table-tr[data-v-f2a183a6] {
            display: -webkit-box;
            display: flex;
            flex-wrap: wrap;
        }

        .d-table-td[data-v-f2a183a6] {
            padding: 10px 10px 10px 10px;
        }

        .d-table-th[data-v-f2a183a6] {
            padding: 10px 10px 10px 10px;
            font-weight: bold;
        }

        .d-body[data-v-f2a183a6] {
            padding: 50px;
        }

        .d[data-v-f2a183a6] {
            font-size: 0.9em !important;
            color: black;
            background: white;
            min-height: 1000px;
        }

        .d-right[data-v-f2a183a6] {
            text-align: right;
        }

        .d-title[data-v-f2a183a6] {
            font-size: 50px;
            line-height: 50px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .d-header-50[data-v-f2a183a6] {
            -webkit-box-flex: 1;
            flex: 1;
        }

        .d-header-inner[data-v-f2a183a6] {
            display: -webkit-box;
            display: flex;
            padding: 50px;
        }

        .d-header-brand[data-v-f2a183a6] {
            width: 200px;
        }

        .d-logo[data-v-f2a183a6] {
            max-width: 100%;
        }</style>
    <style type="text/css">p[data-v-37eeda86] {
            line-height: 1.2em;
            margin: 0 0 2px 0;
        }

        pre[data-v-37eeda86] {
            margin: 0;
        }

        img[data-v-37eeda86] {
            max-width: 100%;
        }

        .d-table-value[data-v-37eeda86] {
            padding-right: 0;
        }

        .d-table-controls[data-v-37eeda86] {
            -webkit-box-flex: 5;
            flex: 5;
        }

        .d-table-summary[data-v-37eeda86] {
            -webkit-box-flex: 4;
            flex: 4;
        }</style>
    <style type="text/css">p[data-v-e95a8a8c] {
            line-height: 1.2em;
            margin: 0 0 2px 0;
        }

        pre[data-v-e95a8a8c] {
            margin: 0;
        }

        img[data-v-e95a8a8c] {
            max-width: 100%;
        }

        .d[data-v-e95a8a8c] {
            font-family: monospace;
        }

        .fancy-title[data-v-e95a8a8c] {
            margin-top: 0;
            padding-top: 0;
        }

        .d-table-value[data-v-e95a8a8c] {
            padding-right: 0;
        }

        .d-table-controls[data-v-e95a8a8c] {
            -webkit-box-flex: 5;
            flex: 5;
        }

        .d-table-summary[data-v-e95a8a8c] {
            -webkit-box-flex: 4;
            flex: 4;
        }</style>
    <style type="text/css">p[data-v-363339a0] {
            line-height: 1.2em;
            margin: 0 0 2px 0;
        }

        pre[data-v-363339a0] {
            margin: 0;
        }

        img[data-v-363339a0] {
            max-width: 100%;
        }

        .fancy-title[data-v-363339a0] {
            margin-top: 0;
            font-size: 30px;
            line-height: 1.2em;
            padding-top: 0;
        }

        .f-b[data-v-363339a0] {
            font-size: 17px;
            line-height: 1.2em;
        }

        .thank[data-v-363339a0] {
            font-size: 45px;
            line-height: 1.2em;
            text-align: right;
            font-style: italic;
            padding-right: 25px;
        }

        .f-remarks[data-v-363339a0] {
            padding-left: 25px;
        }

        .d-table-value[data-v-363339a0] {
            padding-right: 0;
        }

        .d-table-controls[data-v-363339a0] {
            -webkit-box-flex: 5;
            flex: 5;
        }

        .d-table-summary[data-v-363339a0] {
            -webkit-box-flex: 4;
            flex: 4;
        }</style>
    <style type="text/css">p[data-v-e23d9750] {
            line-height: 1.2em;
            margin: 0 0 2px 0;
        }

        pre[data-v-e23d9750] {
            margin: 0;
        }

        img[data-v-e23d9750] {
            max-width: 100%;
        }

        .fancy-title[data-v-e23d9750] {
            margin-top: 0;
            font-size: 40px;
            line-height: 1.2em;
            font-weight: bold;
            padding: 25px;
            margin-right: 25px;
        }

        .f-b[data-v-e23d9750] {
            font-size: 17px;
            line-height: 1.2em;
        }

        .thank[data-v-e23d9750] {
            font-size: 45px;
            line-height: 1.2em;
            text-align: right;
            font-style: italic;
            padding-right: 25px;
        }

        .f-remarks[data-v-e23d9750] {
            padding: 25px;
        }

        .d-table-value[data-v-e23d9750] {
            padding-right: 0;
        }

        .d-table-controls[data-v-e23d9750] {
            -webkit-box-flex: 5;
            flex: 5;
        }

        .d-table-summary[data-v-e23d9750] {
            -webkit-box-flex: 4;
            flex: 4;
        }</style>
    <style type="text/css">p[data-v-4b3dcb8a] {
            line-height: 1.2em;
            margin: 0 0 2px 0;
        }

        pre[data-v-4b3dcb8a] {
            margin: 0;
        }

        img[data-v-4b3dcb8a] {
            max-width: 100%;
        }

        .fancy-title[data-v-4b3dcb8a] {
            margin-top: 0;
            padding-top: 0;
        }

        .sub-title[data-v-4b3dcb8a] {
            margin: 5px 0 3px 0;
            display: block;
        }

        .d-table-value[data-v-4b3dcb8a] {
            padding-right: 0;
        }

        .d-table-controls[data-v-4b3dcb8a] {
            -webkit-box-flex: 5;
            flex: 5;
        }

        .d-table-summary[data-v-4b3dcb8a] {
            -webkit-box-flex: 4;
            flex: 4;
        }</style>
    <style type="text/css">p[data-v-1ad6e3b9] {
            line-height: 1.2em;
            margin: 0 0 2px 0;
        }

        pre[data-v-1ad6e3b9] {
            margin: 0;
        }

        img[data-v-1ad6e3b9] {
            max-width: 100%;
        }

        .fancy-title[data-v-1ad6e3b9] {
            margin-top: 0;
            padding-top: 0;
        }

        .sub-title[data-v-1ad6e3b9] {
            margin: 5px 0 3px 0;
            display: block;
        }

        .d-no-pad[data-v-1ad6e3b9] {
            padding: 0px;
        }

        .grey-box[data-v-1ad6e3b9] {
            padding: 50px;
            background: #f8f8f8;
        }

        .d-inner-2[data-v-1ad6e3b9] {
            padding: 50px;
        }</style>
    <style type="text/css">p[data-v-136bf9b5] {
            line-height: 1.2em;
            margin: 0 0 2px 0;
        }

        pre[data-v-136bf9b5] {
            margin: 0;
        }

        img[data-v-136bf9b5] {
            max-width: 100%;
        }

        .fancy-title[data-v-136bf9b5] {
            margin-top: 0;
            padding-top: 0;
        }

        .d-table-value[data-v-136bf9b5] {
            padding-right: 0px;
        }</style>
    <style type="text/css">p[data-v-7d9d14b5] {
            line-height: 1.2em;
            margin: 0 0 2px 0;
        }

        pre[data-v-7d9d14b5] {
            margin: 0;
        }

        img[data-v-7d9d14b5] {
            max-width: 100%;
        }

        .fancy-title[data-v-7d9d14b5] {
            margin-top: 0;
            padding-top: 0;
        }

        .sub-title[data-v-7d9d14b5] {
            margin: 0 0 5px 0;
        }

        .padd[data-v-7d9d14b5] {
            margin-left: 5px;
            padding-left: 5px;
            border-left: 1px solid #f8f8f8;
            margin-right: 5px;
            padding-right: 5px;
            border-right: 1px solid #f8f8f8;
        }

        .d-inner[data-v-7d9d14b5] {
            padding-right: 0px;
        }

        .d-table-value[data-v-7d9d14b5] {
            padding-right: 5px;
        }

        .d-table-controls[data-v-7d9d14b5] {
            -webkit-box-flex: 5;
            flex: 5;
        }

        .d-table-summary[data-v-7d9d14b5] {
            -webkit-box-flex: 4;
            flex: 4;
        }</style>
    <style type="text/css">p[data-v-b8f60a0c] {
            line-height: 1.2em;
            margin: 0 0 2px 0;
        }

        pre[data-v-b8f60a0c] {
            margin: 0;
        }

        img[data-v-b8f60a0c] {
            max-width: 100%;
        }

        .fancy-title[data-v-b8f60a0c] {
            margin-top: 0;
            padding-top: 10px;
        }

        .d-table-value[data-v-b8f60a0c] {
            padding-right: 0;
        }

        .d-table-controls[data-v-b8f60a0c] {
            -webkit-box-flex: 5;
            flex: 5;
        }

        .d-table-summary[data-v-b8f60a0c] {
            -webkit-box-flex: 4;
            flex: 4;
        }

        .overflow-x-hidden {
            overflow-x: hidden !important;
        }

        .mb-5
        {
            margin-bottom: 10px;
        }
        .float-right
        {
            float: right;
        }
    </style>
 <?php if($settings_data['SITE_RTL']=='on'): ?>
        <link rel="stylesheet" href="<?php echo e(asset('css/bootstrap-rtl.css')); ?>">
    <?php endif; ?>
</head>
<body class="">

<div class="container">
    <div id="app" class="content">
        <div class="editor">
            <div class="invoice-preview-inner">
                <div class="editor-content">
                    <div class="preview-main client-preview">
                        <div data-v-363339a0="" class="d" style="width:710px;margin-left: auto;margin-right: auto;" id="boxes">
                            <div data-v-363339a0="" class="d-inner">
                                <div data-v-363339a0="" class="row">
                                    <div data-v-363339a0="" class="col-2"><h1 data-v-363339a0="" class="fancy-title tu mb5" style="color: <?php echo e($color); ?>;"><?php echo e(__('BILL')); ?></h1>
                                        <p data-v-363339a0=""><?php if($settings['company_name']): ?><?php echo e($settings['company_name']); ?><?php endif; ?></p>
                                        <?php if($settings['company_email']): ?><?php echo e($settings['company_email']); ?><?php endif; ?><br>
                                        <?php if($settings['company_telephone']): ?><?php echo e($settings['company_telephone']); ?><?php endif; ?><br>
                                        <pre data-v-363339a0=""><?php if($settings['company_address']): ?><?php echo e($settings['company_address']); ?><?php endif; ?></pre>
                                        <p data-v-363339a0=""><?php if($settings['company_city']): ?> <?php echo e($settings['company_city']); ?>, <?php endif; ?>
                                            <?php if($settings['company_state']): ?><?php echo e($settings['company_state']); ?><?php endif; ?>
                                            </p>
                                        <p data-v-363339a0=""><?php if($settings['company_country']): ?> <?php echo e($settings['company_country']); ?><?php endif; ?>
                                            <?php if($settings['company_zipcode']): ?> - <?php echo e($settings['company_zipcode']); ?><?php endif; ?></p> <br>
                                        <?php if(!empty($settings['registration_number'])): ?><?php echo e(__('Registration Number')); ?> : <?php echo e($settings['registration_number']); ?> <?php endif; ?><br>
                                        <?php if(!empty($settings['tax_type']) && !empty($settings['vat_number'])): ?><?php echo e($settings['tax_type'].' '. __('Number')); ?> : <?php echo e($settings['vat_number']); ?> <br><?php endif; ?>
                                    </div>
                                    <div data-v-363339a0="" class="col-2 text-right">
                                        <img data-v-363339a0="" src="<?php echo e($img); ?>" style="max-width: 250px" class="d-logo">
                                        <div class="mb-5 float-right">
                                            <?php echo DNS2D::getBarcodeHTML(route('bill.link.copy',\Crypt::encrypt($bill->bill_id)), "QRCODE",2,2); ?>

                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div data-v-363339a0="" class="col-3">

                                    </div>
                                    <div data-v-363339a0="" class="col-1 text-right ">
                                        <div data-v-363339a0="" class="">
                                            <table data-v-363339a0="" class="summary-table">
                                                <tbody data-v-363339a0="">
                                                <tr data-v-363339a0="">
                                                    <td data-v-363339a0="" class="tu fwb" style="color: <?php echo e($color); ?>;"><?php echo e(__('Number')); ?>:</td>
                                                    <td data-v-363339a0="" class="text-right"><?php echo e(Utility::billNumberFormat($settings,$bill->bill_id)); ?></td>
                                                </tr>
                                                <tr data-v-363339a0="">
                                                    <td data-v-363339a0="" class="tu fwb" style="color: <?php echo e($color); ?>;"><?php echo e(__('Issue Date')); ?>:</td>
                                                    <td data-v-363339a0="" class="text-right"><?php echo e(Utility::dateFormat($settings,$bill->bill_date)); ?></td>
                                                </tr>
                                                <tr data-v-363339a0="">
                                                    <td data-v-363339a0="" class="tu fwb" style="color: <?php echo e($color); ?>;"><?php echo e(__('Due Date')); ?>:</td>
                                                    <td data-v-363339a0="" class="text-right"><?php echo e(Utility::dateFormat($settings,$bill->due_date)); ?></td>
                                                </tr>
                                                 <?php if(!empty($customFields) && count($bill->customField)>0): ?>
                                                    <?php $__currentLoopData = $customFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <tr data-v-37eeda86="">
                                                            <td  data-v-37eeda86="" class="tu"><?php echo e($field->name); ?> :</td>
                                                            <td data-v-37eeda86="" class="text-right"> <?php echo e(!empty($bill->customField)?$bill->customField[$field->id]:'-'); ?></td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mt-5">
                                    <div class="bill_to">
                                        <strong data-v-f2a183a6=""><?php echo e(__('Bill To')); ?>:</strong>
                                        <p>
                                            <?php echo e(!empty($vendor->billing_name)?$vendor->billing_name:''); ?><br>
                                            <?php echo e(!empty($vendor->billing_phone)?$vendor->billing_phone:''); ?><br>
                                            <?php echo e(!empty($vendor->billing_address)?$vendor->billing_address:''); ?><br>
                                            <?php echo e(!empty($vendor->billing_city)?$vendor->billing_city:'' .', '); ?>,<?php echo e(!empty($vendor->billing_state)?$vendor->billing_state:'',', '); ?>,<?php echo e(!empty($vendor->billing_country)?$vendor->billing_country:''); ?><br>
                                            <?php echo e(!empty($vendor->billing_zip)?$vendor->billing_zip:''); ?><br>
                                        </p>
                                    </div>
                                    <?php if($settings['shipping_display']=='on'): ?>
                                        <div class="ship_to">
                                            <strong data-v-f2a183a6=""><?php echo e(__('Ship To')); ?>:</strong>
                                            <p>
                                                <?php echo e(!empty($vendor->shipping_name)?$vendor->shipping_name:''); ?><br>
                                                <?php echo e(!empty($vendor->shipping_phone)?$vendor->shipping_phone:''); ?><br>
                                                <?php echo e(!empty($vendor->shipping_address)?$vendor->shipping_address:''); ?><br>
                                                <?php echo e(!empty($vendor->shipping_city)?$vendor->shipping_city:'' . ', '); ?>,<?php echo e(!empty($vendor->shipping_state)?$vendor->shipping_state:'' .', '); ?>,<?php echo e(!empty($vendor->shipping_country)?$vendor->shipping_country:''); ?><br>
                                                <?php echo e(!empty($vendor->shipping_zip)?$vendor->shipping_zip:''); ?><br>
                                            </p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div data-v-363339a0="" class="break-25"></div>
                                <div data-v-363339a0="" class="d-table">
                                    <div data-v-363339a0="" class="d-table">

                                        <div data-v-f2a183a6="" class="d-table-tr" style="background: <?php echo e($color); ?>;color:<?php echo e($font_color); ?>">
                                            <div class="d-table-th w-4"><?php echo e(__('Item')); ?></div>
                                            <div class="d-table-th w-3"><?php echo e(__('Quantity')); ?></div>
                                            <div class="d-table-th w-3"><?php echo e(__('Rate')); ?></div>
                                            <div class="d-table-th w-5"><?php echo e(__('Tax')); ?> (%)</div>
                                            <div class="d-table-th w-4"><?php echo e(__('Discount')); ?></div>
                                            <div class="d-table-th w-4"><?php echo e(__('Price')); ?><br><small class="text-danger"><?php echo e(__('before tax & discount')); ?></small>
                                            </div>
                                        </div>

                                        <div class="d-table-body">
                                            <?php if(isset($bill->itemData) && count($bill->itemData) > 0): ?>
                                                <?php $__currentLoopData = $bill->itemData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                                    <div class="d-table-tr" style="border-bottom:1px solid <?php echo e($color); ?>;">
                                                        <div class="d-table-td w-4">
                                                            <pre data-v-f2a183a6=""><?php echo e($item->name); ?></pre>
                                                        </div>
                                                        <div class="d-table-td w-3">
                                                            <pre data-v-f2a183a6=""><?php echo e($item->quantity); ?></pre>
                                                        </div>
                                                        <div class="d-table-td w-3">
                                                            <pre data-v-f2a183a6=""><?php echo e(Utility::priceFormat($settings,$item->price)); ?></pre>
                                                        </div>
                                                        <div class="d-table-td w-5">
                                                                <pre data-v-f2a183a6="">
                                                                  <?php if(!empty($item->itemTax)): ?>
                                                                        <?php $__currentLoopData = $item->itemTax; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $taxes): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <span><?php echo e($taxes['name']); ?></span>  <span>(<?php echo e($taxes['rate']); ?>)</span> <span><?php echo e($taxes['price']); ?></span>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    <?php else: ?>
                                                                        -
                                                                    <?php endif; ?>
                                                                </pre>
                                                        </div>

                                                            <div class="d-table-td w-4">
                                                                <pre data-v-f2a183a6=""><?php echo e(($item->discount!=0)?Utility::priceFormat($settings,$item->discount):'-'); ?></pre>
                                                            </div>

                                                        <div class="d-table-td w-4"><span><?php echo e(Utility::priceFormat($settings,$item->price * $item->quantity)); ?></span></div>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php else: ?>
                                                <div class="d-table-tr" style="border-bottom:1px solid <?php echo e($color); ?>;">
                                                    <div class="d-table-td w-2"><span>-</span></div>
                                                    <div class="d-table-td w-7">
                                                        <pre data-v-f2a183a6="">-</pre>
                                                    </div>
                                                    <div class="d-table-td w-5">
                                                        <pre data-v-f2a183a6="">-</pre>
                                                    </div>
                                                    <div class="d-table-td w-5">
                                                        <pre data-v-f2a183a6="">-</pre>
                                                    </div>
                                                    <div class="d-table-td w-4"><span>-</span></div>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="d-table-tr" style="border-bottom:1px solid <?php echo e($color); ?>;">
                                            <div class="d-table-td w-4">
                                                <pre data-v-f2a183a6=""><?php echo e(__('Total')); ?></pre>
                                            </div>
                                            <div class="d-table-td w-3">
                                                <pre data-v-f2a183a6=""><?php echo e($bill->totalQuantity); ?></pre>
                                            </div>
                                            <div class="d-table-td w-4">
                                                <pre data-v-f2a183a6=""><?php echo e(Utility::priceFormat($settings,$bill->totalRate)); ?></pre>
                                            </div>
                                            <div class="d-table-td w-5">
                                                <pre data-v-f2a183a6=""><?php echo e(Utility::priceFormat($settings,$bill->totalTaxPrice)); ?></pre>
                                            </div>

                                                <div class="d-table-td w-4">
                                                    <pre data-v-f2a183a6=""><?php echo e(Utility::priceFormat($settings,$bill->totalDiscount)); ?></pre>
                                                </div>

                                            <div class="d-table-td w-4">
                                                    <span><?php echo e(Utility::priceFormat($settings,$bill->getSubTotal())); ?>

                                                    </span>
                                            </div>
                                        </div>

                                        <div data-v-f2a183a6="" class="d-table-footer">
                                            <div data-v-f2a183a6="" class="d-table-controls"></div>
                                            <div data-v-f2a183a6="" class="d-table-summary">
                                                <div data-v-f2a183a6="" class="d-table-summary-item">
                                                    <div data-v-f2a183a6="" class="d-table-label"><?php echo e(__('Subtotal')); ?>:</div>
                                                    <div data-v-f2a183a6="" class="d-table-value"><?php echo e(Utility::priceFormat($settings,$bill->getSubTotal())); ?></div>
                                                </div>
                                                <?php if($bill->getTotalDiscount()): ?>
                                                    <div data-v-f2a183a6="" class="d-table-summary-item">
                                                        <div data-v-f2a183a6="" class="d-table-label"><?php echo e(__('Discount')); ?>:</div>
                                                        <div data-v-f2a183a6="" class="d-table-value"><?php echo e(Utility::priceFormat($settings,$bill->getTotalDiscount())); ?></div>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if(!empty($bill->taxesData)): ?>
                                                    <?php $__currentLoopData = $bill->taxesData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $taxName => $taxPrice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <div data-v-f2a183a6="" class="d-table-summary-item">
                                                            <div data-v-f2a183a6="" class="d-table-label"><?php echo e($taxName); ?> :</div>
                                                            <div data-v-f2a183a6="" class="d-table-value"><?php echo e(Utility::priceFormat($settings,$taxPrice)); ?></div>
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                                <div data-v-f2a183a6="" class="d-table-summary-item">
                                                    <div data-v-f2a183a6="" class="d-table-label"><?php echo e(__('Total')); ?>:</div>
                                                    <div data-v-f2a183a6="" class="d-table-value"><?php echo e(Utility::priceFormat($settings,$bill->getSubTotal()-$bill->getTotalDiscount()+$bill->getTotalTax())); ?></div>
                                                </div>
                                                <div data-v-f2a183a6="" class="d-table-summary-item">
                                                    <div data-v-f2a183a6="" class="d-table-label"><?php echo e(__('Paid')); ?>:</div>
                                                    <div data-v-f2a183a6="" class="d-table-value"><?php echo e(Utility::priceFormat($settings,($bill->getTotal()-$bill->getDue())-($bill->billTotalDebitNote()))); ?></div>
                                                </div>
                                                <div data-v-f2a183a6="" class="d-table-summary-item">
                                                    <div data-v-f2a183a6="" class="d-table-label"><?php echo e(__('Debit Note')); ?>:</div>
                                                    <div data-v-f2a183a6="" class="d-table-value"><?php echo e(Utility::priceFormat($settings,($bill->billTotalDebitNote()))); ?></div>
                                                </div>
                                                <div data-v-f2a183a6="" class="d-table-summary-item">
                                                    <div data-v-f2a183a6="" class="d-table-label"><?php echo e(__('Due Amount')); ?>:</div>
                                                    <div data-v-f2a183a6="" class="d-table-value"><?php echo e(Utility::priceFormat($settings,$bill->getDue())); ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div data-v-f2a183a6="" class="d-header-50">
                                    <p data-v-f2a183a6="">
                                        <?php echo e($settings['footer_title']); ?> <br>
                                        <?php echo e($settings['footer_notes']); ?>

                                    </p>
                                </div>
                                <div data-v-363339a0="" class="break-50"></div>
                                <div data-v-363339a0="" class="row">
                                    <div data-v-363339a0="" class="col-66"><p data-v-363339a0="" class="thank" style="color: <?php echo e($color); ?>;"><?php echo e(__('Thank you')); ?>!</p></div>
                                    <div data-v-363339a0="" class="col-33"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if(!isset($preview)): ?>
    <?php echo $__env->make('bill.script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>;
<?php endif; ?>
</body>
</html>
<?php /**PATH /home1/earthdlp/admin.imontechnologies.in/resources/views/bill/templates/template4.blade.php ENDPATH**/ ?>