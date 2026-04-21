
<?php $__env->startSection('page-title', 'My Submissions'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
  <h1>My Submission History</h1>
  <a href="<?php echo e(route('evaluator.dashboard')); ?>" class="btn btn-secondary">← Assigned Janitors</a>
</div>

<form method="GET" action="<?php echo e(route('evaluator.history')); ?>">
  <div class="filter-bar">
    <div class="form-group">
      <label class="form-label">Search Janitor</label>
      <input type="text" name="search" class="form-control" placeholder="Janitor name…"
             value="<?php echo e(request('search')); ?>">
    </div>
    <div class="form-group" style="align-self:flex-end">
      <button type="submit" class="btn btn-secondary">Search</button>
      <a href="<?php echo e(route('evaluator.history')); ?>" class="btn btn-secondary" style="margin-left:6px">Clear</a>
    </div>
  </div>
</form>

<div class="card">
  <div class="card-header">
    <h2>Evaluations</h2>
    <span style="font-size:12px; color:var(--muted); margin-left:auto"><?php echo e($evaluations->total()); ?> record(s)</span>
  </div>
  <div class="table-wrap">
    <table class="data-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Janitor</th>
          <th>Area</th>
          <th>Date</th>
          <th style="text-align:center">Score /100</th>
          <th>Rating</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $evaluations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
          <td style="color:var(--muted)"><?php echo e($evaluations->firstItem() + $loop->index); ?></td>
          <td style="font-weight:600"><?php echo e($ev->janitor->name); ?></td>
          <td style="font-size:12px; color:var(--muted)"><?php echo e($ev->area->name); ?></td>
          <td><?php echo e($ev->eval_date->format('m/d/Y')); ?></td>
          <td style="text-align:center; font-size:16px; font-weight:700"><?php echo e($ev->total_score); ?></td>
          <td>
            <?php $r = $ev->rating_label; ?>
            <span class="badge <?php echo e($r === 'Excellent' ? 'badge-excellent' : ($r === 'Satisfactory' ? 'badge-satisfactory' : 'badge-needs')); ?>">
              <?php echo e($r); ?>

            </span>
          </td>
          <td>
            <a href="<?php echo e(route('evaluator.evaluate.show', $ev)); ?>" class="btn btn-secondary btn-sm">View</a>
          </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr>
          <td colspan="7" style="text-align:center; padding:32px; color:var(--muted)">
            No submissions yet.
            <a href="<?php echo e(route('evaluator.dashboard')); ?>" style="color:var(--navy-lt)">Go evaluate a janitor.</a>
          </td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  <?php if($evaluations->hasPages()): ?>
  <div class="card-footer" style="display:flex; justify-content:flex-end">
    <?php echo e($evaluations->links('vendor.pagination.simple')); ?>

  </div>
  <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\qmmc_laravel\qmmc_laravel\resources\views/evaluator/history.blade.php ENDPATH**/ ?>