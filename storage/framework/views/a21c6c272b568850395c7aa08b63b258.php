
<?php $__env->startSection('page-title', 'Janitor Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
  <h1>Janitor Management</h1>
  <a href="<?php echo e(route('admin.janitors.create')); ?>" class="btn btn-primary">
    + Add Janitor
  </a>
</div>


<form method="GET" action="<?php echo e(route('admin.janitors.index')); ?>">
  <div class="filter-bar">
    <div class="form-group">
      <label class="form-label">Search</label>
      <input type="text" name="search" class="form-control" placeholder="Name or Employee ID…"
             value="<?php echo e(request('search')); ?>">
    </div>
    <div class="form-group">
      <label class="form-label">Status</label>
      <select name="status" class="form-control">
        <option value="">All</option>
        <option value="active"   <?php echo e(request('status') === 'active'   ? 'selected' : ''); ?>>Active</option>
        <option value="inactive" <?php echo e(request('status') === 'inactive' ? 'selected' : ''); ?>>Inactive</option>
      </select>
    </div>
    <div class="form-group" style="align-self:flex-end">
      <button type="submit" class="btn btn-secondary">Filter</button>
      <a href="<?php echo e(route('admin.janitors.index')); ?>" class="btn btn-secondary" style="margin-left:6px">Clear</a>
    </div>
  </div>
</form>

<div class="card">
  <div class="card-header">
    <h2>Janitors</h2>
    <span style="font-size:12px; color:var(--muted); margin-left:auto">
      <?php echo e($janitors->total()); ?> record(s)
    </span>
  </div>
  <div class="table-wrap">
    <table class="data-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Employee ID</th>
          <th>Assigned Areas</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $janitors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $janitor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
          <td style="color:var(--muted)"><?php echo e($loop->iteration); ?></td>
          <td style="font-weight:600"><?php echo e($janitor->name); ?></td>
          <td><?php echo e($janitor->employee_id ?? '—'); ?></td>
          <td>
            <?php if($janitor->areas->isEmpty()): ?>
              <span style="color:var(--muted)">No areas assigned</span>
            <?php else: ?>
              <?php $__currentLoopData = $janitor->areas->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <span class="badge" style="background:#EEF2FF; color:#3730A3; margin:1px;"><?php echo e($area->name); ?></span>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              <?php if($janitor->areas->count() > 3): ?>
                <span style="font-size:11px; color:var(--muted)">+<?php echo e($janitor->areas->count() - 3); ?> more</span>
              <?php endif; ?>
            <?php endif; ?>
          </td>
          <td>
            <span class="badge <?php echo e($janitor->is_active ? 'badge-active' : 'badge-inactive'); ?>">
              <?php echo e($janitor->is_active ? 'Active' : 'Inactive'); ?>

            </span>
          </td>
          <td>
            <div class="td-actions">
              <a href="<?php echo e(route('admin.janitors.edit', $janitor)); ?>" class="btn btn-secondary btn-sm">Edit</a>
              <form method="POST" action="<?php echo e(route('admin.janitors.destroy', $janitor)); ?>"
                    onsubmit="return confirm('Delete <?php echo e($janitor->name); ?>? This cannot be undone.')">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
              </form>
            </div>
          </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr>
          <td colspan="6" style="text-align:center; padding:32px; color:var(--muted)">
            No janitors found. <a href="<?php echo e(route('admin.janitors.create')); ?>" style="color:var(--navy-lt)">Add the first one.</a>
          </td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  <?php if($janitors->hasPages()): ?>
  <div class="card-footer" style="display:flex; justify-content:flex-end">
    <?php echo e($janitors->links('vendor.pagination.simple')); ?>

  </div>
  <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\qmmc_laravel\qmmc_laravel\resources\views/admin/janitors/index.blade.php ENDPATH**/ ?>