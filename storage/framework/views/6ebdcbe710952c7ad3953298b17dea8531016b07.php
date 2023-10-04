<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('POS Barcode Print')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('pos.barcode')); ?>"><?php echo e(__('POS Product Barcode')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('POS Barcode Print')); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('css-page'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/datatable/buttons.dataTables.min.css')); ?>">
    <style>
        body{
            font-family: 'Montserrat', sans-serif;
        }
        .barcodeBreak{
            width: 100px;
            height: 50px;
            padding-left: 20px
        }
        .barcode_detail p{
            font-size: 4px;
        }
        .barcode_detail p:first-child{
            padding-right: 10px;
        }
        .tech-head{
            font-size: 4px;
        }
        @page  {size: 595px 842px; margin:0!important; padding:0!important}
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script-page'); ?>

    <script type="text/javascript" src="<?php echo e(asset('js/html2pdf.bundle.min.js')); ?>"></script>

    <script>
        var filename = $('#filesname').val();

        function saveAsPDF() {
            var element = document.getElementById('printableArea');

            var opt = {
                margin: 0,
                padding:0,
                filename: filename,
                image: { type: 'jpeg', quality:1},
                html2canvas: { 
                    dpi: 271,
                    letterRendering: true,
                    useCORS: true,
                    windowWidth:200,
                    windowHeight:200,
                    scale : 4
                },
                jsPDF: { unit: 'in', format: [0.9,0.6], orientation: 'landscape' },        
                pagebreak: { mode: 'avoid-all', after: '.barcodeBreak' }
            };
            html2pdf().set(opt).from(element).toPdf().get('pdf').then(function (pdf) {
                window.open(pdf.output('bloburl'), '_blank');
            });

        }
    </script>
<?php $__env->stopPush(); ?>


<?php $__env->startSection('action-btn'); ?>
    <div class="float-end">
        <a href="<?php echo e(route('pos.barcode')); ?>" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="<?php echo e(__('Back')); ?>">
            <i class="ti ti-arrow-left text-white"></i>
        </a>
        <button class="btn btn-sm btn-primary" onclick="saveAsPDF()">
            <?php echo e(__('Print')); ?>

        </a>
    </div>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row" id="printableArea">
                        <?php if(count($purchase) > 0): ?> 
                            <?php $__currentLoopData = $purchase; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="barcodeBreak">
                                    <div class="barcode mt-2">
                                        <div class="barcode_detail">
                                            <p class="tech-head m-0"><b>Imon </b>Technologies</p>
                                        </div>
                                        <?php echo DNS1D::getBarcodeHTML($item->barcode, "C128", 0.4, 20); ?> 
                                        
                                        <div class="barcode_detail d-flex">
                                            <p class="pid"><?php echo e($item->barcode); ?></p>
                                            <p class="m-0"><?php echo e($item->sr_no); ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                        <div class="barcode">
                            <p class="pt-2 px-3">No Barcode Found.</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/hp/Documents/CNS/idea/resources/views/pos/print.blade.php ENDPATH**/ ?>