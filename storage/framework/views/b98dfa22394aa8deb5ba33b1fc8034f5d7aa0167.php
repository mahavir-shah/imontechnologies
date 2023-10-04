<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('ProductKit Detail')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('script-page'); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('productkit.index')); ?>"><?php echo e(__('Product Kits')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Product Kit View')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>             

<div class="row">
	<div class="col-12">
        <div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-md-6 ">
						<div class="form-group">
							<?php echo e(Form::label('kit_name', __('Name'),['class'=>'form-label'])); ?>: <?php echo e($productKit->kit_name); ?>

						</div>
					</div> 
				</div>
			</div>
		</div>
	</div>
	
	<div class="col-12">
	    <h5 class=" d-inline-block mb-4"><?php echo e(__('Product & Services')); ?></h5>
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
							<?php
								$totalAllianceSellingPrice = 0;
								$totalPremiumSellingPrice = 0;
								$totalStandardSellingPrice = 0;
								$totalCostPrice = 0;
							?>
							<?php $__currentLoopData = $product_info; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<tr>
								<td class="product-img">
									<img src="<?php echo e(\Storage::url('uploads/pro_image/').$pi['product_details']->pro_image); ?>" alt="product img"/>
								</td>
								<td>
									<?php echo e($pi['product_details']->name); ?>                
								</td>
								<td>
									<?php echo e($pi['quantity']); ?> 
								</td>
								<td>
									<?php echo e($pi['alliance_selling_price']); ?>

									<?php
										$totalAllianceSellingPrice += (float) str_replace(',', '', $pi['alliance_selling_price']);
									?>
								</td>
								<td>
									<?php echo e($pi['premium_selling_price']); ?>   
									<?php
										$totalPremiumSellingPrice += (float) str_replace(',', '', $pi['premium_selling_price']);
									?>
								</td>
								<td>
									<?php echo e($pi['standard_selling_price']); ?>

									<?php
										$totalStandardSellingPrice += (float) str_replace(',', '', $pi['standard_selling_price']);
									?>
								</td>
								<td>
									<?php echo e($pi['cost_price']); ?>

									<?php
										$totalCostPrice += str_replace(',', '', $pi['cost_price']);
									?>
								</td>
								
							</tr>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="3"><b>Total:</b></td>
								<td class="total_alliance_selling_price"><?php echo e(number_format($totalAllianceSellingPrice)); ?></td>
								<td class="total_premium_selling_price"><?php echo e(number_format($totalPremiumSellingPrice)); ?></td>
								<td class="total_standard_selling_price"><?php echo e(number_format($totalStandardSellingPrice)); ?></td>
								<td class="total_cost_price"><?php echo e(number_format($totalCostPrice)); ?></td>
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
						<?php echo e(Form::label('kit_name', __('Total Alliance Product Price'),['class'=>'form-label'])); ?>: &nbsp;&nbsp; <?php echo e(number_format($productKit->total_alliance_purchase_price)); ?>

					</div>
				</div>
				
				<div class="col-md-6">
					<div class="form-group">
						<?php echo e(Form::label('kit_name', __('Total Cost Price'),['class'=>'form-label'])); ?>: &nbsp;&nbsp; <?php echo e(number_format($productKit->total_cost_price)); ?>

					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<?php echo e(Form::label('kit_name', __('Total Premium Product Price'),['class'=>'form-label'])); ?>: &nbsp;&nbsp; <?php echo e(number_format($productKit->total_premium_purchase_price)); ?>

					</div>
				</div>
				
				<div class="col-md-6"></div>
			
				<div class="col-md-6">
					<div class="form-group">
						<?php echo e(Form::label('kit_name', __('Total Standard Product Price'),['class'=>'form-label'])); ?>: &nbsp;&nbsp; <?php echo e(number_format($productKit->total_standard_purchase_price)); ?>

					</div>
				</div>
				
				<div class="col-md-6"></div>
			</div>
		</div>
	</div>
	 <div class="modal-footer">
		<input type="button" value="<?php echo e(__('Back')); ?>" class="btn  btn-primary" onclick="location.href =<?php echo route('purchase.index'); ?>" />
	</div>	
	<?php echo e(Form::close()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/imontechnologies/resources/views/productkit/show.blade.php ENDPATH**/ ?>