@extends('layouts.admin')
@section('page-title')
    {{__('ProductKit Detail')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('productkit.index')}}">{{__('Product Kits')}}</a></li>
    <li class="breadcrumb-item">{{__('Product Kit View')}}</li>
@endsection

@section('content')             

<div class="row">
	<div class="col-12">
        <div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-md-6 ">
						<div class="form-group">
							{{ Form::label('kit_name', __('Name'),['class'=>'form-label']) }}: {{ $productKit->kit_name }}
						</div>
					</div> 
				</div>
			</div>
		</div>
	</div>
	
	<div class="col-12">
	    <h5 class=" d-inline-block mb-4">{{__('Product & Services')}}</h5>
        <div class="card">
			<div class="card-body table-border-style">
				<div class="table-responsive">
					<table class="table mb-0" data-repeater-list="items" id="sortable-table">
						<thead>
							<tr>
								<th scope="col">Product Image</th>
								<th scope="col">Product</th>
								<th scope="col">Quantity</th>
								<th scope="col">Alliance Selling Price</th>
								<th scope="col">Premium Selling Price</th>
								<th scope="col">Standard Selling Price</th>
								<th scope="col">Cost Price</th>
							</tr>
						</thead>
						<tbody>
							@php
								$totalAllianceSellingPrice = 0;
								$totalPremiumSellingPrice = 0;
								$totalStandardSellingPrice = 0;
								$totalCostPrice = 0;
							@endphp
							@foreach($product_info as $pi)
							<tr>
								<td class="product-img">
									<img src="{{ \Storage::url('uploads/pro_image/').$pi['product_details']->pro_image }}" alt="product img"/>
								</td>
								<td>
									{{ $pi['product_details']->name }}                
								</td>
								<td>
									{{ $pi['quantity'] }} 
								</td>
								<td>
									{{ $pi['alliance_selling_price'] }}
									@php
										$totalAllianceSellingPrice += (float) str_replace(',', '', $pi['alliance_selling_price']);
									@endphp
								</td>
								<td>
									{{ $pi['premium_selling_price'] }}   
									@php
										$totalPremiumSellingPrice += (float) str_replace(',', '', $pi['premium_selling_price']);
									@endphp
								</td>
								<td>
									{{ $pi['standard_selling_price'] }}
									@php
										$totalStandardSellingPrice += (float) str_replace(',', '', $pi['standard_selling_price']);
									@endphp
								</td>
								<td>
									{{ $pi['cost_price'] }}
									@php
										$totalCostPrice += str_replace(',', '', $pi['cost_price']);
									@endphp
								</td>
								
							</tr>
							@endforeach
						</tbody>
						<tfoot>
							<tr>
								<td colspan="3"><b>Total:</b></td>
								<td class="total_alliance_selling_price">{{ number_format($totalAllianceSellingPrice) }}</td>
								<td class="total_premium_selling_price">{{ number_format($totalPremiumSellingPrice) }}</td>
								<td class="total_standard_selling_price">{{ number_format($totalStandardSellingPrice) }}</td>
								<td class="total_cost_price">{{  number_format($totalCostPrice) }}</td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
		
	<div class="col-12">
        <div class="card">		
			<div class="card-body row">
				<div class="col-md-6">
					<div class="form-group">
						{{ Form::label('kit_name', __('Total Alliance Product Price'),['class'=>'form-label']) }}: &nbsp;&nbsp; {{  number_format($productKit->total_alliance_purchase_price) }}
					</div>
				</div>
				
				<div class="col-md-6">
					<div class="form-group">
						{{ Form::label('kit_name', __('Total Cost Price'),['class'=>'form-label']) }}: &nbsp;&nbsp; {{  number_format($productKit->total_cost_price) }}
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						{{ Form::label('kit_name', __('Total Premium Product Price'),['class'=>'form-label']) }}: &nbsp;&nbsp; {{  number_format($productKit->total_premium_purchase_price) }}
					</div>
				</div>
				
				<div class="col-md-6"></div>
			
				<div class="col-md-6">
					<div class="form-group">
						{{ Form::label('kit_name', __('Total Standard Product Price'),['class'=>'form-label']) }}: &nbsp;&nbsp; {{  number_format($productKit->total_standard_purchase_price) }}
					</div>
				</div>
				
				<div class="col-md-6"></div>
			</div>
		</div>
	</div>
	 <div class="modal-footer">
		<input type="button" value="{{__('Back')}}" class="btn  btn-primary" onclick="location.href =<?php echo route('purchase.index'); ?>" />
	</div>	
	{{Form::close()}}
</div>
@endsection
