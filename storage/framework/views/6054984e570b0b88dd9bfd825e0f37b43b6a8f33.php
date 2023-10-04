<script>
    $(".allow_decimal").on("input", function(evt) {
        var self = $(this);
        self.val(self.val().replace(/[^0-9\.]/g, ''));
        if ((evt.which != 46 || self.val().indexOf('.') != -1) && (evt.which < 48 || evt.which > 57)) 
        {
            evt.preventDefault();
        }
    });
</script>
<?php echo e(Form::open(array('url' => 'clients'))); ?>

<div class="modal-body">
    <div class="row">
        <div class="form-group">
            <?php echo e(Form::label('name', __('Name'),['class'=>'form-label'])); ?>

            <?php echo e(Form::text('name', null, array('class' => 'form-control','placeholder'=>__('Enter client Name'),'required'=>'required'))); ?>

        </div>
        <div class="form-group">
            <?php echo e(Form::label('email', __('E-Mail Address'),['class'=>'form-label'])); ?>

            <?php echo e(Form::email('email', null, array('class' => 'form-control','placeholder'=>__('Enter Client Email'),'required'=>'required'))); ?>

        </div>
        <div class="form-group">
            <?php echo e(Form::label('password', __('Password'),['class'=>'form-label'])); ?>

            <?php echo e(Form::password('password',array('class'=>'form-control','placeholder'=>__('Enter User Password'),'required'=>'required','minlength'=>"6"))); ?>

            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <small class="invalid-password" role="alert">
                <strong class="text-danger"><?php echo e($message); ?></strong>
            </small>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="form-group">
            <?php echo e(Form::label('client_type', __('Type'),['class'=>'form-label'])); ?>

            <?php echo e(Form::select('client_type', $clientType, null, array('class' => 'form-control select','required'=>'required'))); ?>

        </div>
        <div class="form-group">
            <?php echo e(Form::label('client_credit_line', __('Credit Line'),['class'=>'form-label'])); ?>

            <?php echo e(Form::text('client_credit_line', null, array('class' => 'form-control allow_decimal','placeholder'=>__('Enter credit line'),'required'=>'required'))); ?>

        </div>
        <?php if(!$customFields->isEmpty()): ?>
            <?php echo $__env->make('custom_fields.formBuilder', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>

    </div>
</div>

<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Create')); ?>" class="btn  btn-primary">
</div>

<?php echo e(Form::close()); ?>



<?php /**PATH /home1/earthdlp/admin.imontechnologies.in/resources/views/clients/create.blade.php ENDPATH**/ ?>