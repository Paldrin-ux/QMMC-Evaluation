
<?php $__env->startSection('page-title', 'Evaluator Area Assignments'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
  <h1>Evaluator Area Assignments</h1>
</div>

<div style="font-size:13px;color:var(--muted);margin-bottom:18px;background:var(--bg);border:1px solid var(--border);border-radius:8px;padding:10px 14px">
  Assign <strong>areas</strong> to each evaluator. All janitors assigned to those areas will automatically appear in the evaluator's dashboard.
</div>

<?php $__currentLoopData = $evaluators; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $evaluator): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php $assignedIds = $evaluator->assignedAreas->pluck('id')->toArray(); ?>

<div class="card" style="margin-bottom:16px">
  <div class="card-header" style="background:var(--bg)">
    <div style="display:flex;align-items:center;gap:10px;flex:1">
      <div style="width:32px;height:32px;border-radius:50%;background:var(--navy);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#fff">
        <?php echo e(strtoupper(substr($evaluator->name, 0, 2))); ?>

      </div>
      <div>
        <div style="font-weight:600;font-size:14px"><?php echo e($evaluator->name); ?></div>
        <div style="font-size:11px;color:var(--muted)"><?php echo e($evaluator->email); ?></div>
      </div>
      <span class="badge badge-evaluator" style="margin-left:8px">Evaluator</span>
    </div>
    <span style="font-size:12px;color:var(--muted)">
      <?php echo e(count($assignedIds)); ?> area(s) assigned
    </span>
  </div>

  <div class="card-body">
    <form method="POST" action="<?php echo e(route('admin.assignments.update')); ?>">
      <?php echo csrf_field(); ?>
      <input type="hidden" name="evaluator_id" value="<?php echo e($evaluator->id); ?>">

      <input type="text" placeholder="Search areas…"
             oninput="filterItems(this,'block_<?php echo e($evaluator->id); ?>')"
             class="form-control" style="margin-bottom:10px;max-width:300px">

      <div id="block_<?php echo e($evaluator->id); ?>"
           style="display:grid;grid-template-columns:repeat(auto-fill,minmax(230px,1fr));gap:6px;max-height:240px;overflow-y:auto;padding:2px">
        <?php $__currentLoopData = $areas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $checked = in_array($area->id, $assignedIds); ?>
        <label class="item-row" data-name="<?php echo e(strtolower($area->name)); ?>"
               style="display:flex;align-items:center;gap:8px;padding:7px 10px;border:1px solid var(--border);border-radius:7px;cursor:pointer;font-size:13px;transition:background .1s;
               <?php echo e($checked ? 'background:#EEF7EE;border-color:#A8D5A8;' : ''); ?>">
          <input type="checkbox" name="area_ids[]" value="<?php echo e($area->id); ?>"
                 style="width:14px;height:14px;accent-color:var(--navy-lt);flex-shrink:0"
                 <?php echo e($checked ? 'checked' : ''); ?>

                 onchange="toggleStyle(this)">
          <span style="font-size:12px"><?php echo e($area->name); ?></span>
        </label>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>

      <div style="display:flex;align-items:center;gap:10px;margin-top:12px;flex-wrap:wrap">
        <button type="submit" class="btn btn-primary btn-sm">Save Assignments</button>
        <button type="button" class="btn btn-secondary btn-sm"
                onclick="bulkToggle('block_<?php echo e($evaluator->id); ?>',true)">Select All</button>
        <button type="button" class="btn btn-secondary btn-sm"
                onclick="bulkToggle('block_<?php echo e($evaluator->id); ?>',false)">Clear All</button>
        <?php if($evaluator->assignedAreas->isNotEmpty()): ?>
        <span style="font-size:12px;color:var(--muted);margin-left:4px">
          Assigned: <?php echo e($evaluator->assignedAreas->pluck('name')->join(', ')); ?>

        </span>
        <?php endif; ?>
      </div>
    </form>
  </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php if($evaluators->isEmpty()): ?>
<div class="card">
  <div class="card-body" style="text-align:center;padding:40px;color:var(--muted)">
    No evaluator accounts found.
    <a href="<?php echo e(route('admin.users.create')); ?>" style="color:var(--navy-lt)">Add an evaluator account first.</a>
  </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function filterItems(input, blockId) {
  const q = input.value.toLowerCase();
  document.querySelectorAll('#' + blockId + ' .item-row').forEach(row => {
    row.style.display = row.dataset.name.includes(q) ? '' : 'none';
  });
}

function toggleStyle(cb) {
  const label = cb.closest('label');
  label.style.background  = cb.checked ? '#EEF7EE' : '';
  label.style.borderColor = cb.checked ? '#A8D5A8' : '';
}

function bulkToggle(blockId, state) {
  document.querySelectorAll('#' + blockId + ' input[type="checkbox"]').forEach(cb => {
    if (cb.closest('.item-row').style.display !== 'none') {
      cb.checked = state;
      toggleStyle(cb);
    }
  });
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\qmmc_laravel\qmmc_laravel\resources\views/admin/assignments/index.blade.php ENDPATH**/ ?>