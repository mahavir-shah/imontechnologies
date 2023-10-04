<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Product Kit Edit')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('productkit.index')); ?>"><?php echo e(__('Product Kits')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Product Kit Edit')); ?></li>
<?php $__env->stopSection(); ?>
 
<?php $__env->startPush('script-page'); ?>
    <script src="<?php echo e(asset('js/jquery-ui.min.js')); ?>"></script>  
    <script>
		$(document).on('change', '.item', function () {
			var itam_id = $(this).val();
			var url = $(this).data('url');
			var el = $(this);
			$.ajax({
				url: url,
				type: 'POST',
				headers: {
					'X-CSRF-TOKEN': jQuery("#csrf-token").val()
				},
				data: {
					'product_id': itam_id , 
					'_token' : '<?php echo e(csrf_token()); ?>'
				},
				cache: false,
				success: function (data) {
					var item = JSON.parse(data);
					if(item.product != undefined){
						$(el).attr('alliance_selling_price',item.product_price.alliance);
						$(el).attr('premium_selling_price',item.product_price.premium);
						$(el).attr('standard_selling_price',item.product_price.standard);
						
						$(el).attr('cost_price',item.product.purchase_price);
						$(el).parents('tr').find('.quantity').val($(el).parents('tr').find('.quantity').val() == "" ? 1 : parseFloat($(el).parents('tr').find('.quantity').val()));
						$(el).parents('tr').find('.alliance_selling_price').val(formatMoney(item.product_price.alliance*parseFloat($(el).parents('tr').find('.quantity').val())));
						$(el).parents('tr').find('.premium_selling_price').val(formatMoney(item.product_price.premium*parseFloat($(el).parents('tr').find('.quantity').val())));
						$(el).parents('tr').find('.standard_selling_price').val(formatMoney(item.product_price.standard*parseFloat($(el).parents('tr').find('.quantity').val())));
						
						$(el).parents('tr').find('.cost_price').val(formatMoney(item.product.purchase_price));
						$(el).parents('tr').find('.product-img img').attr('src',"<?php echo e(\Storage::url('uploads/pro_image/')); ?>"+item.product.pro_image);
						setTimeout(function(){
							var total_alliance_selling_price = total_premium_selling_price = total_standard_selling_price = total_cost_price = 0;
							$('table#sortable-table tbody tr').each(function(key,value){
								total_alliance_selling_price += parseFloat($(value).find('.alliance_selling_price').val().toString().replace(/,/g , ''));
								total_premium_selling_price += parseFloat($(value).find('.premium_selling_price').val().toString().replace(/,/g , ''));
								total_standard_selling_price += parseFloat($(value).find('.standard_selling_price').val().toString().replace(/,/g , ''));
								
								total_cost_price += parseFloat($(value).find('.cost_price').val().toString().replace(/,/g , ''));
							});  
							$('table#sortable-table tfoot tr td.total_alliance_selling_price').text(formatMoney(total_alliance_selling_price));
							$('table#sortable-table tfoot tr td.total_premium_selling_price').text(formatMoney(total_premium_selling_price));
							$('table#sortable-table tfoot tr td.total_standard_selling_price').text(formatMoney(total_standard_selling_price));
							$('table#sortable-table tfoot tr td.total_cost_price').text(formatMoney(total_cost_price));
						},500);
					}
					
				},
			});
		});
		
		$(document).on('change', '.quantity', function () {
			var total_alliance_selling_price = total_premium_selling_price = total_standard_selling_price = total_cost_price = 0;
			var el = this;
			
			let currentAllianceSellPrice = formatMoney(parseFloat($(el).parents('tr').find('.item').attr('alliance_selling_price'))*$(el).val());
			let currentPremiumSellPrice = formatMoney(parseFloat($(el).parents('tr').find('.item').attr('premium_selling_price'))*$(el).val());
			let currentStandardSellPrice = formatMoney(parseFloat($(el).parents('tr').find('.item').attr('standard_selling_price'))*$(el).val());
			
			let CurrentCostPrice = formatMoney(parseFloat($(el).parents('tr').find('.item').attr('cost_price'))*$(el).val());
			
			$(el).parents('tr').find('.alliance_selling_price').val(currentAllianceSellPrice);
			$(el).parents('tr').find('.premium_selling_price').val(currentPremiumSellPrice);
			$(el).parents('tr').find('.standard_selling_price').val(currentStandardSellPrice);
			
			$(el).parents('tr').find('.cost_price').val(CurrentCostPrice); 
			
			$('table#sortable-table tbody tr').each(function(key,value){
				total_alliance_selling_price += parseFloat($(value).find('.alliance_selling_price').val().toString().replace(/,/g , ''));
				total_premium_selling_price += parseFloat($(value).find('.premium_selling_price').val().toString().replace(/,/g , ''));
				total_standard_selling_price += parseFloat($(value).find('.standard_selling_price').val().toString().replace(/,/g , ''));
				
				total_cost_price += parseFloat($(value).find('.cost_price').val().toString().replace(/,/g , ''));
			});  
			$('table#sortable-table tfoot tr td.total_alliance_selling_price').text(formatMoney(total_alliance_selling_price));
			$('table#sortable-table tfoot tr td.total_premium_selling_price').text(formatMoney(total_premium_selling_price));
			$('table#sortable-table tfoot tr td.total_standard_selling_price').text(formatMoney(total_standard_selling_price));
			
			$('table#sortable-table tfoot tr td.total_cost_price').text(formatMoney(total_cost_price));
		});
		
		$(document).on('click','.add-product-btn',function(){
			$('table#sortable-table tbody').append($('#add-product-code table tbody').html());
			$('table#sortable-table tbody tr').eq($('table#sortable-table tbody tr').length -1).find('.item').change();
		})
		
		$(document).on('click','.repeater-action-btn',function(){
			$(this).parents('tr').remove();
			$('.item').eq(0).change(); 
		});
		
		$(document).on('click','.copy-alliance-total-purchase,.copy-premium-total-purchase,.copy-standard-total-purchase,.copy-total-cost',function(){
			if($(this).hasClass('copy-alliance-total-purchase')){
				$('.total_alliance_purchase_price_input').val($('.total_alliance_selling_price')?.text()?.toString()?.replace(/,/g , ''));
			}
			if($(this).hasClass('copy-premium-total-purchase')){
				$('.total_premium_purchase_price_input').val($('.total_premium_selling_price')?.text()?.toString()?.replace(/,/g , ''));
			}
			if($(this).hasClass('copy-standard-total-purchase')){
				$('.total_standard_purchase_price_input').val($('.total_standard_selling_price')?.text()?.toString()?.replace(/,/g , ''));
			}
			
			if($(this).hasClass('copy-total-cost')){
				$('.total_cost_price_input').val($('.total_cost_price')?.text()?.toString()?.replace(/,/g , ''));
			}
		});
		
		$(document).on('change','.alliance_selling_price, .premium_selling_price, .standard_selling_price',function(){
			if($(this).hasClass('alliance_selling_price')){
				var totalAllincekitSellingCost = 0;
				$('input.alliance_selling_price').each(function(key,value){
					if($(value).val() != ""){
						totalAllincekitSellingCost += parseFloat($(value).val());
					}
				});
				$('table#sortable-table tfoot tr td.total_alliance_selling_price').text(formatMoney(totalAllincekitSellingCost));
			}else if($(this).hasClass('premium_selling_price')){
				var totalPremiumkitSellingCost = 0;
				$('input.premium_selling_price').each(function(key,value){
					if($(value).val() != ""){
						totalPremiumkitSellingCost += parseFloat($(value).val());
					}
				});
				$('table#sortable-table tfoot tr td.total_premium_selling_price').text(formatMoney(totalPremiumkitSellingCost));
			}else if($(this).hasClass('standard_selling_price')){
				var totalStandardkitSellingCost = 0;
				$('input.standard_selling_price').each(function(key,value){
					if($(value).val() != ""){
						totalStandardkitSellingCost += parseFloat($(value).val());
					}
				});
				$('table#sortable-table tfoot tr td.total_standard_selling_price').text(formatMoney(totalStandardkitSellingCost));
			}
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
	</script> 
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}

.cost_price {
	background-color: inherit !important;
	border: 0px !important;
}
</style>
<?php $__env->startSection('content'); ?>
<div class="row">
	<?php echo e(Form::open(array('route' => array('productkit.update', $productKit->id), 'method' => 'PUT','class'=>'w-100'))); ?>

	<div class="col-12">
        <div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-md-6 ">
						<div class="form-group">
							<?php echo e(Form::label('kit_name', __('Name'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
							<div class="form-icon-user">
								<?php echo e(Form::text('kit_name', $productKit->kit_name, array('class' => 'form-control','required'=>'required'))); ?>

							</div>
						</div>
					</div> 
				</div>
			</div>
		</div>
	</div>
	
	<div class="col-12">
	    <h5 class=" d-inline-block mb-4"><?php echo e(__('Product & Services')); ?></h5>
        <div class="card">
			<div class="item-section py-2">
				<div class="row justify-content-between align-items-center">
					<div class="col-md-12 d-flex align-items-center justify-content-between justify-content-md-end">
						<div class="all-button-box me-2">
							<a href="javascript:void(0);" class="btn btn-primary add-product-btn">
								<i class="ti ti-plus"></i> <?php echo e(__('Add item')); ?>

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
								<th scope="col">Product Image</th>
								<th scope="col">Product</th>
								<th scope="col">Quantity</th>
								<th scope="col">Alliance Selling Price</th>
								<th scope="col">Premium Selling Price</th>
								<th scope="col">Standard Selling Price</th>
								<th scope="col">Cost Price</th>
								<th scope="col"></th>
							</tr>
						</thead>
						<tbody>
							<?php
								$totalAllianceSellingPrice = 0;
								$totalPremiumSellingPrice = 0;
								$totalStandardSellingPrice = 0;
								$totalCostPrice = 0;
								$i = 0;
							?>
							<?php $__currentLoopData = $product_info; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<tr>
									<td class="product-img">
										<img src="" alt="product img"/>                    
									</td>
									<td width="30%">
										<?php echo e(Form::select('product_id[]', $products,$pi['product_id'], 
											array(
											'class' => 'form-control item product-lst',
											'data-url'=>route('purchase.product'),
											'required'=>'required',
											'alliance_selling_price'=>(float) str_replace(',', '', $pi['alliance_selling_price']),
											'premium_selling_price'=>(float) str_replace(',', '', $pi['premium_selling_price']),
											'standard_selling_price'=>(float) str_replace(',', '', $pi['standard_selling_price']),
											'cost_price'=>(float) str_replace(',', '', $pi['cost_price']),
											))); ?>                    
									</td>
									<td>
										<?php echo e(Form::text('quantity[]',$pi['quantity'], array('class' => 'form-control quantity','required'=>'required','placeholder'=>__('Qty'),'required'=>'required'))); ?>                    
									</td>
									<td>
										<?php
											$AllianceSellingPrice =((float) str_replace(',', '', $pi['alliance_selling_price']));
											$totalAllianceSellingPrice += $AllianceSellingPrice;
										?>
										<?php echo e(Form::text('alliance_selling_price[]',$AllianceSellingPrice, array('class' => 'form-control alliance_selling_price','required'=>'required','placeholder'=>"",'required'=>'required' ))); ?>

									</td>
									<td>
										<?php
											$PremiumSellingPrice = ((float) str_replace(',', '', $pi['premium_selling_price']));
											$totalPremiumSellingPrice += $PremiumSellingPrice;
										?>
										<?php echo e(Form::text('premium_selling_price[]',$PremiumSellingPrice, array('class' => 'form-control premium_selling_price','required'=>'required','placeholder'=>"",'required'=>'required' ))); ?>

									</td>
									<td>
										<?php
											$StandardSellingPrice = ((float) str_replace(',', '', $pi['standard_selling_price']));
											$totalStandardSellingPrice += $StandardSellingPrice;
										?>
										<?php echo e(Form::text('standard_selling_price[]',$StandardSellingPrice, array('class' => 'form-control standard_selling_price','required'=>'required','placeholder'=>"",'required'=>'required'))); ?>

									</td>
									<td>
										<?php
											$CostPrice = ((float) str_replace(',', '', $pi['cost_price']));
											$totalCostPrice += $CostPrice;
											
										?>
										<?php echo e(Form::text('cost_price[]',$CostPrice, array('class' => 'form-control cost_price','required'=>'required','placeholder'=>"",'required'=>'required','readonly'=>true ))); ?>

									</td>
									<td>
									<?php if($i != 0): ?>
										<a href="#" class="ti ti-trash text-white text-white repeater-action-btn bg-danger ms-2"></a> 
									<?php endif; ?>
									</td>
									<?php
										$i = 1;
									?>
								</tr>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="3"><b>Total:</b></td>
								<td class="total_alliance_selling_price"><?php echo e(number_format($totalAllianceSellingPrice,2)); ?></td>
								<td class="total_premium_selling_price"><?php echo e(number_format($totalPremiumSellingPrice,2)); ?></td>
								<td class="total_standard_selling_price"><?php echo e(number_format($totalStandardSellingPrice,2)); ?></td>
								<td class="total_cost_price"><?php echo e(number_format($totalCostPrice,2)); ?></td>
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
				<div class="col-md-4">
					<div class="form-group">
						<?php echo e(Form::label('kit_name', __('Total Alliance Product Price'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
						<div class="form-icon-user">
							<?php echo e(Form::text('total_alliance_purchase_price', $productKit->total_alliance_purchase_price, array('class' => 'form-control total_alliance_purchase_price_input','required'=>'required'))); ?>

						</div>
					</div>
				</div>
				<div class="col-md-2">					
					<div class="form-group">
						<div>&nbsp;</div>
						<a href="javascript:void(0);" class="copy-alliance-total-purchase">Copy Total Alliance Purchase &nbsp; <i class="fas fa-clipboard"></i></a>
					</div>
				</div>

				<div class="col-md-4">
					<div class="form-group">
						<?php echo e(Form::label('kit_name', __('Total Cost Price'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
						<div class="form-icon-user">
							<?php echo e(Form::text('total_cost_price', $productKit->total_cost_price, array('class' => 'form-control total_cost_price_input','required'=>'required'))); ?>

						</div>
					</div>
				</div>
				<div class="col-md-2">					
					<div class="form-group">
						<div>&nbsp;</div>
						<a href="javascript:void(0);" class="copy-total-cost">Copy Total Cost &nbsp; <i class="fas fa-clipboard"></i></a>
					</div>
				</div>
				
				<div class="col-md-4">
					<div class="form-group">
						<?php echo e(Form::label('kit_name', __('Total Premium Product Price'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
						<div class="form-icon-user">
							<?php echo e(Form::text('total_premium_purchase_price', $productKit->total_premium_purchase_price, array('class' => 'form-control total_premium_purchase_price_input','required'=>'required'))); ?>

						</div>
					</div>
				</div>
				<div class="col-md-2">					
					<div class="form-group">
						<div>&nbsp;</div>
						<a href="javascript:void(0);" class="copy-premium-total-purchase">Copy Premium Total Purchase &nbsp; <i class="fas fa-clipboard"></i></a>
					</div>
				</div>
				<div class="col-md-6"></div>
				
				<div class="col-md-4">
					<div class="form-group">
						<?php echo e(Form::label('kit_name', __('Total Standard Product Price'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
						<div class="form-icon-user">
							<?php echo e(Form::text('total_standard_purchase_price', $productKit->total_standard_purchase_price, array('class' => 'form-control total_standard_purchase_price_input','required'=>'required'))); ?>

						</div>
					</div>
				</div>
				<div class="col-md-2">					
					<div class="form-group">
						<div>&nbsp;</div>
						<a href="javascript:void(0);" class="copy-standard-total-purchase">Copy Standard Total Purchase &nbsp; <i class="fas fa-clipboard"></i></a>
					</div>
				</div>
				<div class="col-md-6"></div>

			</div>
		</div>
	</div>
	 <div class="modal-footer">
		<input type="button" value="<?php echo e(__('Cancel')); ?>" onclick="location.href =<?php echo route('purchase.index'); ?>" class="btn btn-light" />
		<input type="submit" value="<?php echo e(__('Update')); ?>" class="btn  btn-primary" />
	</div>	
	<?php echo e(Form::close()); ?>

</div>
<div id="add-product-code" class="d-none">
	<table>
		<tr>
			<td class="product-img">
				<img src="" alt="product image"/>               
			</td>
			<td>
				<?php echo e(Form::select('product_id[]', $products,'', array('class' => 'form-control item product-lst','data-url'=>route('purchase.product'),'required'=>'required'))); ?>                    
			</td>
			<td>
				<?php echo e(Form::text('quantity[]','', array('class' => 'form-control quantity','required'=>'required','placeholder'=>__('Qty'),'required'=>'required'))); ?>                    
			</td>
			<td>
				<?php echo e(Form::text('alliance_selling_price[]','', array('class' => 'form-control alliance_selling_price','required'=>'required','placeholder'=>"",'required'=>'required'))); ?>

			</td>
			<td>
				<?php echo e(Form::text('premium_selling_price[]','', array('class' => 'form-control premium_selling_price','required'=>'required','placeholder'=>"",'required'=>'required'))); ?>

			</td>
			<td>
				<?php echo e(Form::text('standard_selling_price[]','', array('class' => 'form-control standard_selling_price','required'=>'required','placeholder'=>"",'required'=>'required'))); ?>

			</td>
			<td>
				<?php echo e(Form::text('cost_price[]','', array('class' => 'form-control cost_price','required'=>'required','placeholder'=>"",'required'=>'required'))); ?>

			</td>
			<td>
				<a href="#" class="ti ti-trash text-white text-white repeater-action-btn bg-danger ms-2"></a>
            </td>
		</tr>
	</table>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/imontechnologies/resources/views/productkit/edit.blade.php ENDPATH**/ ?>