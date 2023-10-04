<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Pick-Pack-Ship')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Pick-Pack-Ship')); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('css-page'); ?>
    <style>
        .carton_status_link{
            text-decoration: underline;
        }
    </style>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>

    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th> <?php echo e(__('Ship')); ?></th>
                                <th> <?php echo e(__('Customer')); ?></th>
                                <th> <?php echo e(__('Total Amount')); ?></th>
                                <th> <?php echo e(__('Carton')); ?></th>
                                <th> <?php echo e(__('Due Date')); ?></th>
                                <th> <?php echo e(__('Status')); ?></th>
                                <th> <?php echo e(__('Action')); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__currentLoopData = $shipList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shipItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <tr>
                                    <td class="Id"><?php echo e($shipItem->ship_unique); ?></td>
                                    <td> <?php echo e((!empty( $shipItem->customer)?$shipItem->customer->name:'')); ?> </td>
                                    <td><?php echo e(!empty($shipItem->total_amt)? \Auth::user()->priceFormat($shipItem->total_amt) :0); ?></td>
                                    <td><?php echo e($shipItem->carton != null ?  'Carton - ' . $shipItem->carton : '-'); ?></td>
                                    <td><?php echo e($shipItem->created_at != '0000-00-00' ? Auth::user()->dateFormat($shipItem->created_at) : '-'); ?></td>
                                    <td>
                                        <?php if($shipItem->status == 'pending'): ?>
                                            <span class="purchase_status badge p-2 px-3 rounded bg-secondary"> <?php echo e(ucfirst($shipItem->status)); ?> </span>
                                        <?php elseif($shipItem->status == 'picking'): ?>
                                            <span class="purchase_status badge p-2 px-3 rounded bg-warning"> <?php echo e('Ready to pack'); ?> </span>
                                        <?php elseif($shipItem->status == 'packing'): ?>
                                            <span class="purchase_status badge p-2 px-3 rounded bg-info"> <?php echo e('Ready to ship'); ?> </span>
                                        <?php elseif($shipItem->status == 'shipped'): ?>
                                            <span class="purchase_status badge p-2 px-3 rounded bg-success"> <?php echo e('Shipped'); ?> </span> 
                                        <?php else: ?>
                                            <span class="purchase_status badge p-2 px-3 rounded bg-secondary"> <?php echo e(ucfirst($shipItem->status)); ?> </span>   
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($shipItem->status == 'pending'): ?>
                                            
                                            <div class="action-btn bg-warning ms-2">
                                                <a href="<?php echo e(route('ship.showPicking',\Crypt::encrypt($shipItem->id))); ?>" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="<?php echo e(__('Picking')); ?>" data-original-title="<?php echo e(__('Picking')); ?>">
                                                    <i class="ti ti-shopping-cart text-white"></i>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                        <?php if($shipItem->status == 'picking'): ?>
                                            
                                            <div class="action-btn bg-info ms-2">
                                                <a href="<?php echo e(route('ship.showPacking',\Crypt::encrypt($shipItem->id))); ?>" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="<?php echo e(__('Packing')); ?>" data-original-title="<?php echo e(__('Packing')); ?>">
                                                    <i class="ti ti-box text-white"></i>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                        <?php if($shipItem->status == 'packing'): ?>
                                            <div class="action-btn bg-primary ms-2">
                                                <button class="mx-3 btn btn-sm align-items-center" title="<?php echo e(__('Ship')); ?>" data-bs-toggle="tooltip" data-original-title="<?php echo e(__('Ship')); ?>" data-ajax-popup="true" data-size="lg"
                                                data-align="centered" data-url="<?php echo e(route('ship.showShipForm',\Crypt::encrypt($shipItem->id))); ?>" data-title="<?php echo e(__('Ship')); ?>"><i class="ti ti-archive text-white"></i></button>
                                            </div>
                                        <?php endif; ?>
                                        <?php if($shipItem->status != 'Cancelled'): ?>
                                            <div class="action-btn bg-danger ms-2">
                                                <?php echo Form::open(['method' => 'DELETE', 'route' => ['ship.cancelShip', $shipItem->id],'class'=>'delete-form-btn','id'=>'delete-form-'.$shipItem->id]); ?>

                                                <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip" title="<?php echo e(__('Delete')); ?>" data-original-title="<?php echo e(__('Delete')); ?>" data-confirm="<?php echo e(__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')); ?>" data-confirm-yes="document.getElementById('delete-form-<?php echo e($shipItem->id); ?>').submit();">
                                                    <i class="ti ti-trash text-white"></i>
                                                </a>
                                                <?php echo Form::close(); ?>

                                            </div>
                                        <?php endif; ?>
                                        <div class="action-btn bg-success ms-2">
                                            <a href="<?php echo e(route('pos.ship_pdf', Crypt::encrypt($shipItem->id))); ?>" target="_blank" class="mx-3 btn btn-sm align-items-center"  data-bs-toggle="tooltip" title="<?php echo e(__('Download')); ?>" data-original-title="<?php echo e(__('Download')); ?>">
                                                <i class="ti ti-download text-white"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script-page'); ?>
    <script src="<?php echo e(asset('js/jquery-ui.min.js')); ?>"></script>
    <script type="text/javascript">
        $(document).on('submit', '#packItem', function () {
            var statusArr = [];
            console.log("form submited");
            var data = $("#shipAddForm").serialize();
            $('.cartonTable > tbody > tr').not(':first').each(function(){
                var status = $(this).children('.pack-status').text();
                statusArr.push(status);
            })
            if(statusArr.includes('Packing In Progress','Pack')){
                show_toastr('error', 'Please pack an carton');
            }else{
                var carton = 0;
                var cartonArr = $('input[name=carton]').map(function(){
                  return carton += parseInt($(this).val());
                }).get();
                var id = $('#shipId').val();
                if(carton != 0){
                  $.ajax({
                      url: `<?php echo e(route("ship.addPacking")); ?>`,
                      type: 'GET',
                      header:{
                        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                        },
                      data: {
                          'id': id,
                          'carton' : carton,
                          'data' : data
                      },
                      cache: false,
                      success: function (data) {
                          location.reload();
                      }
                  });
                }
            }
        });

        $(document).on('change', '#deliverySelect', function () {
            var delivery = $(this).val();
            if(delivery == 'courier'){
                $('#trackingNumber').prop("disabled", false);
                $('#carrierName').prop("disabled", false);
                $('.carrierNameDiv').removeClass('d-none');
                $('#carrierType').prop("disabled", false);
            }else{
                $('#trackingNumber').prop("disabled", true);
                $('#carrierName').prop("disabled", true);
                $('.carrierNameDiv').addClass('d-none');
                $('#carrierType').prop("disabled", true);
            }
        });

        $(document).on('click','#addCarton', function () {
            var cartonId = $('.cartonTable').find("tr").length;
            var pickStatus = $('.cartonTable tr:last').children('.pack-status').text();
            var totalQty = $('.cartonTable tr:last').children('.total_qty').text();
            $('#packItem').prop('disabled',true);
            if(totalQty == ''){
                show_toastr('error', 'Please Add product into an carton');
            }else{
                if(pickStatus != 'Packing In Progress'){
                    var html = `
                        <tr class="carton_${cartonId}">
                            <td>carton - ${cartonId} <input type="hidden" name="carton_id" value="${cartonId}" class="carton_id"/></td>
                            <td class="total_qty"></td>
                            <td class="pack-status">Packing In Progress</td>
                            <td><a href="#" class="carton_status_link">Pack</a></td>
                        </tr>
                    `;
                    $('.cartonTable > tbody:last-child').append(html);
                    $('.qty_carton').prop('disabled',false);
                    $('.productTable > tbody > tr').not(':first').each(function(){
                        var getRemainQty = $(this).find('.remainQty').text();
                        var getQtyCarton = $(this).find('.qty_carton').text();
                        if(getRemainQty <= 0) {
                            $(this).find('.qty_carton').prop('disabled',true);
                        }
                        $(this).find('.qty_carton').val(0);
                    })
                }else{
                    show_toastr('error', 'Please Pack an carton.');
                }
            }
        });

        $(document).on('change','.qty_carton',function () {
            var qty = parseInt($(this).val());
            var remainQty = parseInt($(this).parent().prev('.remainQty').text());
            var remainOldQty = parseInt($(this).siblings('.remainOldQty').val());
            if(qty < 0 ){
                show_toastr('error', 'Please add right product quntatity.');
            }else{
                if(remainQty < qty){
                    show_toastr('error', 'Quntatity is more than Remaining Quntatity.');
                }else{
                    var newQty = remainQty - qty;
                    if(qty > 0){
                        $(this).parent().prev('.remainQty').text(newQty);
                    }else{
                        $(this).parent().prev('.remainQty').text(remainOldQty);
                    }
                    var allqty = 0;
                    $('.qty_carton').each(function(){
                        allqty += ($(this).val()) != 'Nan' ? parseInt($(this).val()) : 0;
                    });
                    $('.total_qty').text(parseInt(allqty));
                }
            }
        })

        $(document).on('click','.carton_status_link',function() {
            var getParent = $(this).parent().parent().attr('class');
            var total_qty = $(`.${getParent}`).find('.total_qty').text();
            var getQty = [];
            $('.qty_carton').each(function(){
                getQty.push(parseInt($(this).val()));
            })
            if(!getQty.includes("") && total_qty != ''){
                var status =$(this).text();
                if(status == 'Pack'){
                    $(`.${getParent} .pack-status`).text('Packed');
                    $(`.${getParent} .carton_status_link`).text('Open');
                    $('.qty_carton').prop('disabled',true);
                    var allqty = 0;
                    $('.qty_carton').each(function(){
                        allqty += ($(this).val()) != 'Nan' ? parseInt($(this).val()) : 0;
                    });
                }else if(status == 'Open'){
                    $(`.${getParent} .pack-status`).text('Packing In Progress');
                    $(`.${getParent} .carton_status_link`).text('Pack');
                    $('.qty_carton').prop('disabled',false);
                }
            }else{
                show_toastr('error', 'Add product into carton.');
            }

            var statusArr = [];
            $('.cartonTable > tbody > tr').not(':first').each(function(){
                var status = $(this).children('.pack-status').text();
                statusArr.push(status);
            })
            if(!statusArr.includes('Packing In Progress')){
                $('#packItem').removeClass('disabled');
                $('#packItem').prop('disabled',false);
            }
        })
  </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/vsgpintl/admin.imontechnologies.in/resources/views/ship/index.blade.php ENDPATH**/ ?>