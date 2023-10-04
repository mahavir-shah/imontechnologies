<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Purchase Detail')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('script-page'); ?>
    <script>
        $(document).on('click', '.button', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            swal({
                    title: "Do you want to show barcode?",
                    type: "info",
                    confirmButtonClass: "btn-warning",
                    confirmButtonText: "Yes!",
                    showCancelButton: true,
                },
                function() {
                    $.ajax({
                        type: "POST",
                        url: "<?php echo e(route('purchase.showBarcode')); ?>",
                        success: function (data) {
                                    //
                            }         
                    });
            });
        });
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/hp/Documents/CNS/idea/resources/views/purchase/showBarcode.blade.php ENDPATH**/ ?>