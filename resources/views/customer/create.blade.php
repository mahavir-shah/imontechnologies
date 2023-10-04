{{Form::open(array('url'=>'customer','method'=>'post'))}}
<div class="modal-body">

    <h6 class="sub-title">{{__('Basic Info')}}</h6>
    <div class="control-label mt-3">
    <div class="row">
        <div class="col-lg-2 col-md-2 col-sm-2">
            <div class="form-group">
                {{Form::label('name',__('Name')) }}
            </div>
        </div>
        <div class="col-lg-6 col-md-4 col-sm-6">
            <div class="form-group">
                {{Form::text('name',null,array('class'=>'form-control','required'=>'required'))}}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-2 col-md-2 col-sm-2">
            <div class="form-group">
            {{Form::label('contact',__('Contact'),['class'=>'form-label'])}}
            </div>
        </div>
        <div class="col-lg-6 col-md-4 col-sm-6">
            <div class="form-group">
            {{Form::text('contact',null,array('class'=>'form-control','required'=>'required'))}}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-2 col-md-2 col-sm-2">
            <div class="form-group">
            {{Form::label('email',__('Email'),['class'=>'form-label'])}}
            </div>
        </div>
        <div class="col-lg-6 col-md-4 col-sm-6">
            <div class="form-group">
            {{Form::text('email',null,array('class'=>'form-control'))}}
            </div>
        </div>
    </div>
        <!-- <div class="col-lg-6 col-md-4 col-sm-6">
            <div class="form-group">
                {{ Form::label('password', __('Password'),['class'=>'form-label']) }}
                {{Form::password('password',array('class'=>'form-control','placeholder'=>__('Enter User Password'),'required'=>'required','minlength'=>"6"))}}
            </div>
        </div> -->
        <!-- <div class="col-lg-6 col-md-4 col-sm-6">
            <div class="form-group">
                {{Form::label('tax_number',__('Tax Number'),['class'=>'form-label'])}}
                {{Form::text('tax_number',null,array('class'=>'form-control'))}}
            </div>
        </div> -->
        <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-2">
                <div class="form-group">
                {{Form::label('gst_number',__('GST Number'),['class'=>'form-label'])}}
                </div>
            </div>
            <div class="col-lg-6 col-md-4 col-sm-6">
                <div class="form-group">
                {{Form::text('gst_number',null,array('class'=>'form-control'))}}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-2">
                <div class="form-group">
                {{Form::label('pan_number',__('PAN Number'),['class'=>'form-label'])}}
                </div>
            </div>
            <div class="col-lg-6 col-md-4 col-sm-6">
                <div class="form-group">
                {{Form::text('pan_number',null,array('class'=>'form-control'))}}
                </div>
            </div>
        </div>
        <!-- <div class="col-lg-6 col-md-4 col-sm-6">
            <div class="form-group">
                {{Form::label('website',__('Website'),['class'=>'form-label'])}}
                {{Form::text('website',null,array('class'=>'form-control'))}}
            </div>
        </div> -->
        <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-2">
                <div class="form-group">
                {{Form::label('company_name',__('Company Name'),['class'=>'form-label'])}}
                </div>
            </div>
            <div class="col-lg-6 col-md-4 col-sm-6">
                <div class="form-group">
                {{Form::text('company_name',null,array('class'=>'form-control','required'=>'required'))}}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-2">
                <div class="form-group">
                {{Form::label('receivable',__('Receivable'),['class'=>'form-label'])}}
                </div>
            </div>
            <div class="col-lg-6 col-md-4 col-sm-6">
                <div class="form-group">
                {{Form::number('receivable',null,array('class'=>'form-control'))}}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-2">
                <div class="form-group">
                {{Form::label('unused',__('Unused'),['class'=>'form-label'])}}
                </div>
            </div>
            <div class="col-lg-6 col-md-4 col-sm-6">
                <div class="form-group">
                {{Form::number('unused',null,array('class'=>'form-control'))}}
                </div>
            </div>
        </div>
        @if(!$customFields->isEmpty())
            <div class="col-lg-6 col-md-4 col-sm-6">
                <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                    @include('customFields.formBuilder')
                </div>
            </div>
        @endif
    </div>

    <h6 class="sub-title">{{__('Dealer Detail')}}</h6>
    <div class="control-label">
    <div class="row">
        <div class="col-lg-2 col-md-2 col-sm-2">
            <div class="form-group">
            {{Form::label('dealer_category',__('Dealer Category'),array('class'=>'form-label')) }}
            </div>
        </div>
        <div class="col-lg-6 col-md-4 col-sm-6">
            <div class="form-group">
            {{ Form::select('dealer_category', $clientType, null, array('class' => 'form-control select clientType','required'=>'required')) }}
            </div>
        </div>
        </div>
        <div class="row">
        <div class="col-lg-2 col-md-2 col-sm-2">
            <div class="form-group">
            {{Form::label('discount',__('Discount'),array('class'=>'','class'=>'form-label')) }}
            </div>
        </div>
        <div class="col-lg-6 col-md-4 col-sm-6">
            <div class="form-group">
            {{Form::text('discount',20,array('class'=>'form-control discount'))}}
            </div>
        </div>
        </div>
        <div class="row">
        <div class="col-lg-2 col-md-2 col-sm-2">
            <div class="form-group">
            {{Form::label('credit_day',__('Credit Day'),array('class'=>'','class'=>'form-label')) }}
            </div>
        </div>
        <div class="col-lg-6 col-md-4 col-sm-6">
            <div class="form-group">
            {{Form::text('credit_day',null,array('class'=>'form-control'))}}
            </div>
        </div>
        </div>
        <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-2">
                <div class="form-group">
                {{Form::label('credit_limit',__('Credit Limit'),array('class'=>'form-label')) }}
                </div>
            </div>
            <div class="col-lg-6 col-md-4 col-sm-6">
                <div class="form-group">
                {{Form::text('credit_limit',null,array('class'=>'form-control'))}}
                </div>
            </div>
        </div>

    <h6 class="sub-title">{{__('Billing Address')}}</h6>
    <div class="control-label">
    <div class="row">
        <div class="col-lg-2 col-md-2 col-sm-2">
            <div class="form-group">
            {{Form::label('billing_name',__('Name'),array('class'=>'','class'=>'form-label')) }}
            </div>
        </div>
        <div class="col-lg-6 col-md-4 col-sm-6">
            <div class="form-group">
            {{Form::text('billing_name',null,array('class'=>'form-control'))}}
            </div>
        </div>
        </div>
        <div class="row">
        <div class="col-lg-2 col-md-2 col-sm-2">
            <div class="form-group">
            {{Form::label('billing_phone',__('Phone'),array('class'=>'form-label')) }}
            </div>
        </div>
        <div class="col-lg-6 col-md-4 col-sm-6">
            <div class="form-group">
            {{Form::text('billing_phone',null,array('class'=>'form-control'))}}
            </div>
        </div>
        </div>
        <div class="row">
        <div class="col-lg-2 col-md-2 col-sm-2">
            <div class="form-group">
            {{Form::label('billing_address',__('Address'),array('class'=>'form-label')) }}
            </div>
        </div>
        <div class="col-lg-6 col-md-4 col-sm-6">
            <div class="form-group">
            {{Form::textarea('billing_address',null,array('class'=>'form-control','rows'=>3))}}
            </div>
        </div>
        </div>
        <div class="row">
        <div class="col-lg-2 col-md-2 col-sm-2">
            <div class="form-group">
            {{Form::label('billing_city',__('City'),array('class'=>'form-label')) }}
            </div>
        </div>
        <div class="col-lg-6 col-md-4 col-sm-6">
            <div class="form-group">
            {{Form::text('billing_city',null,array('class'=>'form-control'))}}
            </div>
        </div>
        </div>
        <div class="row">
        <div class="col-lg-2 col-md-2 col-sm-2">
            <div class="form-group">
            {{Form::label('billing_state',__('State'),array('class'=>'form-label')) }}
            </div>
        </div>
        <div class="col-lg-6 col-md-4 col-sm-6">
            <div class="form-group">
            {{Form::text('billing_state',null,array('class'=>'form-control'))}}
            </div>
        </div>
        </div>
        <div class="row">
        <div class="col-lg-2 col-md-2 col-sm-2">
            <div class="form-group">
            {{Form::label('billing_country',__('Country'),array('class'=>'form-label')) }}
            </div>
        </div>
        <div class="col-lg-6 col-md-4 col-sm-6">
            <div class="form-group">
            {{Form::text('billing_country',null,array('class'=>'form-control'))}}
            </div>
        </div>
        </div>

        <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-2">
                <div class="form-group">
                {{Form::label('billing_zip',__('Zip Code'),array('class'=>'form-label')) }}
                </div>
            </div>
            <div class="col-lg-6 col-md-4 col-sm-6">
                <div class="form-group">
                {{Form::text('billing_zip',null,array('class'=>'form-control'))}}
                </div>
            </div>
        </div>

    @if(App\Models\Utility::getValByName('shipping_display')=='on')
        <div class="col-md-12 text-end">
            <input type="button" id="billing_data" value="{{__('Shipping Same As Billing')}}" class="btn btn-primary">
        </div>
        <h6 class="sub-title">{{__('Shipping Address')}}</h6>
        <div class="control-label">
        <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-2">
                <div class="form-group">
                {{Form::label('shipping_name',__('Name'),array('class'=>'form-label')) }}
                </div>
            </div>
            <div class="col-lg-6 col-md-4 col-sm-6">
                <div class="form-group">
                {{Form::text('shipping_name',null,array('class'=>'form-control'))}}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-2">
                <div class="form-group">
                {{Form::label('shipping_phone',__('Phone'),array('class'=>'form-label')) }}
                </div>
            </div>
            <div class="col-lg-6 col-md-4 col-sm-6">
                <div class="form-group">
                {{Form::text('shipping_phone',null,array('class'=>'form-control'))}}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-2">
                <div class="form-group">
                {{Form::label('shipping_address',__('Address'),array('class'=>'form-label')) }}
                </div>
            </div>
            <div class="col-lg-6 col-md-4 col-sm-6">
                <div class="form-group">
                <label class="form-label" for="example2cols1Input"></label>
                {{Form::textarea('shipping_address',null,array('class'=>'form-control','rows'=>3))}}
            </div>
        </div>


        <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-2">
                <div class="form-group">
                {{Form::label('shipping_city',__('City'),array('class'=>'form-label')) }}
                </div>
            </div>
            <div class="col-lg-6 col-md-4 col-sm-6">
                <div class="form-group">
                {{Form::text('shipping_city',null,array('class'=>'form-control'))}}
                </div>
            </div>
            </div>
        <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-2">
                <div class="form-group">
                {{Form::label('shipping_state',__('State'),array('class'=>'form-label')) }}
                </div>
            </div>
            <div class="col-lg-6 col-md-4 col-sm-6">
                <div class="form-group">
                {{Form::text('shipping_state',null,array('class'=>'form-control'))}}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-2">
                <div class="form-group">
                {{Form::label('shipping_country',__('Country'),array('class'=>'form-label')) }}
                </div>
            </div>
            <div class="col-lg-6 col-md-4 col-sm-6">
                <div class="form-group">
                {{Form::text('shipping_country',null,array('class'=>'form-control'))}}
                </div>
            </div>
            </div>

        <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-2">
                <div class="form-group">
                {{Form::label('shipping_zip',__('Zip Code'),array('class'=>'form-label')) }}
                </div>
            </div>
            <div class="col-lg-6 col-md-4 col-sm-6">
                <div class="form-group">
                {{Form::text('shipping_zip',null,array('class'=>'form-control'))}}
                </div>
            </div>
        </div>
    @endif

</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn btn-primary">
</div>
{{Form::close()}}
