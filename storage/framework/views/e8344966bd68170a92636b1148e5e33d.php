
<?php $__env->startSection('page-title', 'Account Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
  <h1>Account Management</h1>
  <a href="<?php echo e(route('admin.users.create')); ?>" class="btn btn-primary">+ Add Account</a>
</div>

<form method="GET" action="<?php echo e(route('admin.users.index')); ?>">
  <div class="filter-bar">
    <div class="form-group">
      <label class="form-label">Search</label>
      <input type="text" name="search" class="form-control" placeholder="Name or email…"
             value="<?php echo e(request('search')); ?>">
    </div>
    <div class="form-group">
      <label class="form-label">Role</label>
      <select name="role" class="form-control">
        <option value="">All Roles</option>
        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($role->slug); ?>" <?php echo e(request('role') === $role->slug ? 'selected' : ''); ?>>
            <?php echo e($role->name); ?>

          </option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
    </div>
    <div class="form-group" style="align-self:flex-end">
      <button type="submit" class="btn btn-secondary">Filter</button>
      <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-secondary" style="margin-left:6px">Clear</a>
    </div>
  </div>
</form>

<div class="card">
  <div class="card-header">
    <h2>Users</h2>
    <span style="font-size:12px; color:var(--muted); margin-left:auto"><?php echo e($users->total()); ?> record(s)</span>
  </div>
  <div class="table-wrap">
    <table class="data-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Email</th>
          <th>Role</th>
          <th>Status</th>
          <th>Created</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
          <td style="color:var(--muted)"><?php echo e($loop->iteration); ?></td>
          <td>
            <div style="display:flex; align-items:center; gap:8px">
              <div style="width:28px; height:28px; border-radius:50%; background:var(--navy); display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:700; color:#fff; flex-shrink:0">
                <?php echo e(strtoupper(substr($user->name, 0, 2))); ?>

              </div>
              <span style="font-weight:600"><?php echo e($user->name); ?></span>
              <?php if($user->id === auth()->id()): ?>
                <span style="font-size:10px; color:var(--muted)">(you)</span>
              <?php endif; ?>
            </div>
          </td>
          <td style="color:var(--muted)"><?php echo e($user->email); ?></td>
          <td>
            <span class="badge badge-<?php echo e($user->role->slug); ?>"><?php echo e($user->role->name); ?></span>
          </td>
          <td>
            <span class="badge <?php echo e($user->is_active ? 'badge-active' : 'badge-inactive'); ?>">
              <?php echo e($user->is_active ? 'Active' : 'Inactive'); ?>

            </span>
          </td>
          <td style="color:var(--muted); font-size:12px"><?php echo e($user->created_at->format('m/d/Y')); ?></td>
          <td>
            <div class="td-actions">
              <a href="<?php echo e(route('admin.users.edit', $user)); ?>" class="btn btn-secondary btn-sm">Edit</a>

              <?php if($user->id !== auth()->id()): ?>
              <form method="POST" action="<?php echo e(route('admin.users.toggle_status', $user)); ?>">
                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                <button type="submit" class="btn btn-sm <?php echo e($user->is_active ? 'btn-danger' : 'btn-secondary'); ?>">
                  <?php echo e($user->is_active ? 'Deactivate' : 'Activate'); ?>

                </button>
              </form>
              <?php endif; ?>
            </div>
          </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr>
          <td colspan="7" style="text-align:center; padding:32px; color:var(--muted)">
            No users found.
          </td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  <?php if($users->hasPages()): ?>
  <div class="card-footer" style="display:flex; justify-content:flex-end">
    <?php echo e($users->links('vendor.pagination.simple')); ?>

  </div>
  <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\qmmc_laravel\qmmc_laravel\resources\views/admin/users/index.blade.php ENDPATH**/ ?>