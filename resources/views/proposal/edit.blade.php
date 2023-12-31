@extends('layouts.admin')
@section('page-title')
    {{__('Estimate Edit')}}
@endsection
@push('script-page')
    <script src="{{asset('js/jquery-ui.min.js')}}"></script>
    <script src="{{asset('js/jquery.repeater.min.js')}}"></script>
    <script>
        var selector = "body";
        if ($(selector + " .repeater").length) {
            var $dragAndDrop = $("body .repeaer tbody").sortable({
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
                    ready:

                        function (setIndexes) {
                            $dragAndDrop.on('drop', setIndexes);
                        }

                    ,
                    isFirstItemUndeletable: true
                }
                )
            ;
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
        $.ajax({
                url: "{{route('proposal.termAndCondition')}}",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': jQuery('#token').val()
                },
                data: {
                    'deller_type':$('select[name="customer_id"] option:selected').attr('rel'),
                },
                dataType: 'html',
                success: function (data) {
                    $('#customer-term-con-sec').removeClass("d-none");
                    $('#customer-term-con').html(data);
                },

            });
        $(document).on('change', '#customer', function () {
            $('#customer_detail').removeClass('d-none');
            $('#customer_detail').addClass('d-block');
            $('#customer-box').removeClass('d-block');
            $('#customer-box').addClass('d-none');
            $('#customer-term-con-sec').addClass("d-none");
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
            $.ajax({
                url: "{{route('proposal.termAndCondition')}}",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': jQuery('#token').val()
                },
                data: {
                    'deller_type':$('select[name="customer_id"] option:selected').attr('rel'),
                },
                dataType: 'html',
                success: function (data) {
                    $('#customer-term-con-sec').removeClass("d-none");
                    $('#customer-term-con').html(data);
                },

            });
        });

        $(document).on('click', '#remove', function () {
            $('#customer-box').removeClass('d-none');
            $('#customer-box').addClass('d-block');
            $('#customer_detail').removeClass('d-block');
            $('#customer_detail').addClass('d-none');
        })

        $(document).on('change', '.item', function () {
            changeItem($(this));
        });

        var proposal_id = '{{$proposal->id}}';

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
                        url: '{{route('proposal.items')}}',
                        type: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': jQuery('#token').val()
                        },
                        data: {
                            'proposal_id': proposal_id,
                            'product_id': iteams_id,
                        },
                        cache: false,
                        success: function (data) {
                            var proposalItems = JSON.parse(data);

                            if (proposalItems != null) {
                                var amount = (proposalItems.price * proposalItems.quantity);

                                $(el.parent().parent().find('.quantity')).val(proposalItems.quantity);
                                $(el.parent().parent().find('.price')).val(proposalItems.price);
                                $(el.parent().parent().find('.discount')).val(proposalItems.discount);
                            } else {
                                $(el.parent().parent().find('.quantity')).val(1);
                                $(el.parent().parent().find('.price')).val(item.product.sale_price);
                                $(el.parent().parent().find('.discount')).val(0);
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
                                    taxes += '<span class="badge bg-primary p-2 px-3 rounded mt-1 mr-1">' + item.taxes[i].name + ' ' + '(' + item.taxes[i].rate + '%)' + '</span>';
                                    tax.push(item.taxes[i].id);
                                    totalItemTaxRate += parseFloat(item.taxes[i].rate);
                                }
                            }

                            if (proposalItems != null) {
                                var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (proposalItems.price * proposalItems.quantity));
                            } else {
                                var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (item.product.sale_price * 1));
                            }


                            $(el.parent().parent().find('.itemTaxPrice')).val(itemTaxPrice.toFixed(2));
                            $(el.parent().parent().find('.itemTaxRate')).val(totalItemTaxRate.toFixed(2));
                            $(el.parent().parent().find('.taxes')).html(taxes);
                            $(el.parent().parent().find('.tax')).val(tax);
                            $(el.parent().parent().find('.unit')).html(item.unit);


                            if (proposalItems != null) {
                                $(el.parent().parent().find('.amount')).html(amount);
                            } else {
                                $(el.parent().parent().find('.amount')).html(item.totalAmount);
                            }

                            var inputs = $(".amount");
                            var subTotal = 0;
                            for (var i = 0; i < inputs.length; i++) {
                                subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
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


                            var excut_amont = 0;
                            if($('.edjust-amount').val() == ''){
                                 excut_amont = 0;
                            }else{
                                excut_amont = parseFloat($('.edjust-amount').val());
                            }
                            $('.totalTax').html(totalItemTaxPrice.toFixed(2));
                            $('.totalAmount').html((parseFloat(subTotal) - parseFloat(totalItemDiscountPrice) + excut_amont + parseFloat(totalItemTaxPrice)).toFixed(2));
                            $('.totalAmountInput').val((parseFloat(subTotal) - parseFloat(totalItemDiscountPrice) + excut_amont + parseFloat(totalItemTaxPrice)).toFixed(2));
                            $('.totalDiscount').val(totalItemDiscountPrice.toFixed(2));

                        }
                    });


                },
            });
        }

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
            var excut_amont = 0;
            if($('.edjust-amount').val() == ''){
                 excut_amont = 0;
            }else{
                excut_amont = parseFloat($('.edjust-amount').val());
            }
            $('.totalAmount').html(formatMoney((parseFloat(subTotal) + excut_amont+ parseFloat(totalItemTaxPrice)).toFixed(2)));
            $('.totalAmountInput').val(formatMoney((parseFloat(subTotal) + excut_amont + parseFloat(totalItemTaxPrice)).toFixed(2)));

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
            var excut_amont = 0;
            if($('.edjust-amount').val() == ''){
                 excut_amont = 0;
            }else{
                excut_amont = parseFloat($('.edjust-amount').val());
            }
            $('.subTotal').html(formatMoney(subTotal.toFixed(2)));
            $('.totalAmount').html(formatMoney((parseFloat(subTotal) + excut_amont + parseFloat(totalItemTaxPrice)).toFixed(2)));
            $('.totalAmountInput').val(formatMoney((parseFloat(subTotal) + excut_amont + parseFloat(totalItemTaxPrice)).toFixed(2)));

        })


        $(document).on('keyup', '.discount', function () {
            debugger
            var el = $(this).parent().parent().parent().parent();
            var discount = 0;
            if($(this).val() == ''){
                 discount = 0;
            }else{
                discount = $(this).val();
            }
            console.log(discount);
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

                totalItemDiscountPrice += itemDiscountPriceInput[k].value != '' ? parseFloat(itemDiscountPriceInput[k].value) : 0;
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
            $('.subTotal').html(subTotal.toFixed(2));
            $('.totalDiscount').val(totalItemDiscountPrice.toFixed(2));
            $('.totalTax').html(totalItemTaxPrice.toFixed(2));
            var excut_amont = 0;
            if($('.edjust-amount').val() == ''){
                 excut_amont = 0;
            }else{
                excut_amont = parseFloat($('.edjust-amount').val());
            }
            $('.totalAmount').html((parseFloat(subTotal) - parseFloat(totalItemDiscountPrice) + excut_amont + parseFloat(totalItemTaxPrice)).toFixed(2));
            $('.totalAmountInput').val((parseFloat(subTotal) - parseFloat(totalItemDiscountPrice) + excut_amont + parseFloat(totalItemTaxPrice)).toFixed(2));
            console.log((parseFloat(subTotal) - parseFloat(totalItemDiscountPrice) + parseFloat(totalItemTaxPrice)).toFixed(2));
        })

        $(document).on('keyup', '.add-discount', function () {
            var discount = 0;
            if($(this).val() == ''){
                 discount = 0;
            }else{
                discount = $(this).val();
            }

            var subTotal = $('.subTotal').text();
            var totalItemTaxPrice = $('.totalTax').text();
            var excut_amont = 0;
            if($('.edjust-amount').val() == ''){
                 excut_amont = 0;
            }else{
                excut_amont = parseFloat($('.edjust-amount').val());
            }

            $('.totalAmount').html((parseFloat(subTotal) - parseFloat(discount) + excut_amont + parseFloat(totalItemTaxPrice)).toFixed(2));
            $('.totalAmountInput').val((parseFloat(subTotal) - parseFloat(discount) + excut_amont + parseFloat(totalItemTaxPrice)).toFixed(2));
        });

        $(document).on('keyup', '.edjust-amount', function () {
            var excut_amont = 0;
            if($(this).val() == ''){
                 excut_amont = 0;
            }else{
                excut_amont = parseFloat($(this).val());
            }
            var discount = $('.add-discount').val();
            var subTotal = $('.subTotal').text();
            var totalItemTaxPrice = $('.totalTax').text();
            var totalAmount = (parseFloat(subTotal) - parseFloat(discount) + parseFloat(totalItemTaxPrice)) + excut_amont;
            console.log(totalAmount);
            $('.totalAmount').html(parseFloat(totalAmount).toFixed(2));
            $('.totalAmountInput').val(parseFloat(totalAmount).toFixed(2));

        });

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
                    url: '{{route('proposal.product.destroy')}}',
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
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('estimate.index')}}">{{__('Estimate')}}</a></li>
    <li class="breadcrumb-item">{{__('Estimate Edit')}}</li>
@endsection
@section('content')
    <div class="row">
        {{ Form::model($proposal, array('route' => array('estimate.update', $proposal->id), 'method' => 'PUT','class'=>'w-100')) }}
        <div class="col-12">
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" id="customer-box">
                                    {{ Form::label('customer_id', __('Customer'),['class'=>'form-label']) }}
                                    <select name="customer_id" class="form-control select" id="customer" data-url="{{route('proposal.customer')}}" required>
                                    <option value="">Select Customer</option>
                                    @foreach($customers as $data)
                                        <option value="{{$data->id}}" rel="{{$data->dealer_category}}" {{$proposal->customer_id == $data->id ? 'selected':''}}>{{$data->name}}</option>
                                    @endforeach
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
                                        {{ Form::label('issue_date', __('Estimate Date'),['class'=>'form-label']) }}
                                        <div class="form-icon-user">
                                            {{Form::date('issue_date',null,array('class'=>'form-control','required'=>'required'))}}


                                        </div>
                                    </div>
                                </div>
                                {{--<div class="col-md-6">
                                        {{ Form::label('category_id', __('Category'),['class'=>'form-label']) }}
                                        {{ Form::select('category_id', $category,null, array('class' => 'form-control select','required'=>'required')) }}
                                </div>--}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('proposal_number', __('Estimate Number'),['class'=>'form-label']) }}
                                        <div class="form-icon-user">
                                            <input type="text" class="form-control" value="{{$proposal_number}}" readonly>
                                        </div>
                                    </div>
                                </div>


{{--                                <div class="col-md-6">--}}
{{--                                    <div class="form-check custom-checkbox mt-4">--}}
{{--                                        <input class="form-check-input" type="checkbox" name="discount_apply" id="discount_apply" {{$proposal->discount_apply==1?'checked':''}}>--}}
{{--                                        <label class="form-check-label " for="discount_apply">{{__('Discount Apply')}}</label>--}}
{{--                                    </div>--}}
{{--                                </div>--}}


{{--                                <div class="col-md-6">--}}
{{--                                    <div class="form-group">--}}
{{--                                        {{Form::label('sku',__('SKU')) }}--}}
{{--                                        {!!Form::text('sku', null,array('class' => 'form-control','required'=>'required')) !!}--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                @if(!$customFields->isEmpty())
                                    <div class="col-md-6">
                                        <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                                            @include('customFields.formBuilder')
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <h5 class="d-inline-block mb-4">{{__('Product & Services')}}</h5>
            <div class="card repeater" data-value='{!! json_encode($proposal->items) !!}'>
                <div class="item-section py-2">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-md-12 d-flex align-items-center justify-content-between justify-content-md-end">
                            <div class="all-button-box me-2">
                                <a href="#" data-repeater-create="" class="btn btn-primary" data-bs-toggle="modal" data-target="#add-bank">
                                    <i class="ti ti-plus"></i> {{__('Add Item')}}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0" data-repeater-list="items" id="sortable-table">

                            <thead>
                            <tr>
                                <th>{{__('Items')}}</th>
                                <th>{{__('Quantity')}}</th>
                                <th>{{__('Price')}} </th>
                                <th>{{__('Tax')}} (%)</th>
                                <th>{{__('Discount')}}</th>
                                <th class="text-end">{{__('Amount')}} <br><small class="text-danger font-weight-bold">{{__('before tax & discount')}}</small></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody class="ui-sortable" data-repeater-item>
                            <tr>
                                {{ Form::hidden('id',null, array('class' => 'form-control id')) }}
                                <td width="25%" class="form-group pt-0">
                                    {{ Form::select('item', $product_services,null, array('class' => 'form-control select item','data-url'=>route('proposal.product'))) }}
                                </td>
                                <td>
                                    <div class="form-group price-input input-group search-form">
                                        {{ Form::text('quantity',null, array('class' => 'form-control quantity','required'=>'required','placeholder'=>__('Qty'),'required'=>'required')) }}
                                        <span class="unit input-group-text bg-transparent"></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group price-input input-group search-form">
                                        {{ Form::text('price',null, array('class' => 'form-control price','required'=>'required','placeholder'=>__('Price'),'required'=>'required')) }}
                                        <span class="input-group-text bg-transparent">{{\Auth::user()->currencySymbol()}}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="taxes"></div>
                                            {{ Form::hidden('tax','', array('class' => 'form-control tax')) }}
                                            {{ Form::hidden('itemTaxPrice','', array('class' => 'form-control itemTaxPrice')) }}
                                            {{ Form::hidden('itemTaxRate','', array('class' => 'form-control itemTaxRate')) }}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group price-input input-group search-form">
                                        {{ Form::text('discount',null, array('class' => 'form-control discount','required'=>'required','placeholder'=>__('Discount'))) }}
                                        <span class="input-group-text bg-transparent">{{\Auth::user()->currencySymbol()}}</span>
                                    </div>
                                </td>
                                <td class="text-end amount">0.00</td>
                                <td>
                                    @can('delete proposal product')
                                        <a href="#" class="ti ti-trash text-white repeater-action-btn bg-danger ms-2" data-repeater-delete></a>
                                    @endcan
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="form-group">
                                        {{ Form::textarea('description', null, ['class'=>'form-control','rows'=>'2','placeholder'=>__('Description')]) }}

                                        <p class="text-success hsn_code"><span>HSN Code: 
                                            {{ Form::text('hsn_code', null,['class'=>'text-success border-0']) }}</span></p>
                                    </div>
                                </td>
                                <td colspan="5"></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="form-group">
                                        {{Form::label('customer_note',__('Customer Notes')) }}
                                        {{ Form::textarea('customer_note', null, ['class'=>'form-control','rows'=>'1','placeholder'=>__('Customer Note')]) }}
                                    </div>
                                </td>
                                <td colspan="5"></td>
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td></td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td></td>
                                <td><strong>{{__('Sub Total')}} ({{\Auth::user()->currencySymbol()}})</strong></td>
                                <td class="text-end subTotal">0.00</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td></td>
                                <td><strong>{{__('Discount')}} ({{\Auth::user()->currencySymbol()}})</strong></td>
                                <td class="text-end totalDiscount"><input type="text" name="discount_apply" class="form-control totalDiscount add-discount" value="{{$proposal->discount_apply}}"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td></td>
                                <td><strong>{{__('Tax')}} ({{\Auth::user()->currencySymbol()}})</strong></td>
                                <td class="text-end totalTax">0.00</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td></td>
                                <td><strong>{{__('Edjust Amount')}} ({{\Auth::user()->currencySymbol()}})</strong></td>
                                <td class="text-end"><input type="text" name="edjust_amount" class="form-control  edjust-amount" value="{{$proposal->edjust_amount}}"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td class="blue-text border-none"><strong>{{__('Total Amount')}} ({{\Auth::user()->currencySymbol()}})</strong><input type="hidden" name="total_amount" class="totalAmountInput" value=""></td>
                                <td class="text-end totalAmount blue-text border-none">0.00</td>
                                <td></td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="card d-none" id="customer-term-con-sec">
                <div class="card-header">
                    <h6>{{Form::label('customer_note',__('Terms & Condition')) }}</h6>
                </div>
                <div class="card-body mt-3">
                    <div class="form-group">
                        <p id="customer-term-con"></p>
                    </div>
                </div>
            </div>
        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" onclick="location.href = '{{route("estimate.index")}}';" class="btn btn-light">
            <input type="submit" value="{{__('Update')}}" class="btn btn-primary">
        </div>
        {{ Form::close() }}
    </div>
@endsection

