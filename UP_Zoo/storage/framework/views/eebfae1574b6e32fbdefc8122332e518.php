

<?php $__env->startSection('title', ($brand->exists ? 'Изменить бренд' : 'Новый бренд').' — UP_ZOO'); ?>

<?php $__env->startSection('content'); ?>
    <div class="admin-page-heading"><div><span class="eyebrow">КАТАЛОГ / БРЕНДЫ</span><h1><?php echo e($brand->exists ? 'Изменить бренд' : 'Новый бренд'); ?></h1></div><a class="btn btn-outline-purple" href="<?php echo e(route('admin.brands.index')); ?>">← К списку</a></div>
    <form class="admin-panel admin-form" action="<?php echo e($brand->exists ? route('admin.brands.update', $brand) : route('admin.brands.store')); ?>" method="POST"><?php echo csrf_field(); ?> <?php if($brand->exists): ?><?php echo method_field('PUT'); ?><?php endif; ?><div class="row g-3"><div class="col-md-6"><label class="form-label">Название</label><input class="form-control" name="name" value="<?php echo e(old('name', $brand->name)); ?>" required maxlength="255"></div><div class="col-md-6"><label class="form-label">URL-идентификатор</label><input class="form-control" name="slug" value="<?php echo e(old('slug', $brand->slug)); ?>" required maxlength="255"></div><div class="col-12"><label class="form-label">Описание</label><textarea class="form-control" name="description" rows="3" maxlength="1000"><?php echo e(old('description', $brand->description)); ?></textarea></div><div class="col-12"><button class="btn btn-primary-soft">Сохранить</button></div></div></form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Suorce\VS_CODE\WEB\Mvek\UP_Zoo\resources\views/admin/brands/form.blade.php ENDPATH**/ ?>