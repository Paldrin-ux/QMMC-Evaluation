
<?php $__env->startSection('page-title', 'Admin Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
  <h1>Overview</h1>
</div>

<div class="stat-grid">
  <div class="stat-card accent">
    <div class="label">Total Janitors</div>
    <div class="value"><?php echo e(\App\Models\Janitor::count()); ?></div>
    <div class="sub"><?php echo e(\App\Models\Janitor::where('is_active', true)->count()); ?> active</div>
  </div>
  <div class="stat-card green">
    <div class="label">Evaluations This Month</div>
    <div class="value"><?php echo e(\App\Models\Evaluation::whereMonth('eval_date', now()->month)->whereYear('eval_date', now()->year)->count()); ?></div>
    <div class="sub"><?php echo e(now()->format('F Y')); ?></div>
  </div>
  <div class="stat-card">
    <div class="label">Total Evaluations</div>
    <div class="value"><?php echo e(\App\Models\Evaluation::count()); ?></div>
    <div class="sub">all time</div>
  </div>
  <div class="stat-card">
    <div class="label">Evaluator Accounts</div>
    <div class="value"><?php echo e(\App\Models\User::whereHas('role', fn($q) => $q->where('slug', 'evaluator'))->count()); ?></div>
    <div class="sub">active staff</div>
  </div>
</div>

<?php
  $recentEvals = \App\Models\Evaluation::with(['janitor', 'area', 'evaluator'])
    ->latest('eval_date')->take(8)->get();
  $excellent    = \App\Models\Evaluation::where('rating_label', 'Excellent')->count();
  $satisfactory = \App\Models\Evaluation::where('rating_label', 'Satisfactory')->count();
  $needs        = \App\Models\Evaluation::where('rating_label', 'Needs Improvement')->count();
  $total        = max($excellent + $satisfactory + $needs, 1);
?>

<div style="display:grid; grid-template-columns:2fr 1fr; gap:18px; align-items:start">

  
  <div class="card">
    <div class="card-header">
      <h2>Recent Evaluations</h2>
      <a href="<?php echo e(route('admin.evaluations.index')); ?>" class="btn btn-secondary btn-sm" style="margin-left:auto">View All</a>
    </div>
    <div class="table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>Janitor</th>
            <th>Area</th>
            <th>Date</th>
            <th>Score</th>
            <th>Rating</th>
          </tr>
        </thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $recentEvals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <tr>
            <td style="font-weight:600"><?php echo e($ev->janitor->name); ?></td>
            <td style="font-size:12px; color:var(--muted)"><?php echo e(Str::limit($ev->area->name, 28)); ?></td>
            <td><?php echo e($ev->eval_date->format('m/d/Y')); ?></td>
            <td style="font-weight:700; font-size:15px; text-align:center"><?php echo e($ev->total_score); ?></td>
            <td>
              <?php $r = $ev->rating_label; ?>
              <span class="badge <?php echo e($r === 'Excellent' ? 'badge-excellent' : ($r === 'Satisfactory' ? 'badge-satisfactory' : 'badge-needs')); ?>">
                <?php echo e($r); ?>

              </span>
            </td>
          </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <tr>
            <td colspan="5" style="text-align:center; padding:28px; color:var(--muted)">No evaluations yet.</td>
          </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  
  <div class="card">
    <div class="card-header"><h2>Rating Distribution</h2></div>
    <div class="card-body">
      <?php $__currentLoopData = [
        ['label' => 'Excellent',         'count' => $excellent,    'cls' => 'badge-excellent',    'color' => '#1E7E34'],
        ['label' => 'Satisfactory',      'count' => $satisfactory, 'cls' => 'badge-satisfactory', 'color' => '#856404'],
        ['label' => 'Needs Improvement', 'count' => $needs,        'cls' => 'badge-needs',        'color' => '#842029'],
      ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <div style="margin-bottom:14px">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:5px">
          <span style="font-size:13px; font-weight:600; color:<?php echo e($item['color']); ?>"><?php echo e($item['label']); ?></span>
          <span style="font-size:13px; font-weight:700"><?php echo e($item['count']); ?></span>
        </div>
        <div style="background:var(--border); border-radius:4px; height:8px; overflow:hidden">
          <div style="background:<?php echo e($item['color']); ?>; height:100%; width:<?php echo e(round($item['count'] / $total * 100)); ?>%; border-radius:4px; transition:width .4s"></div>
        </div>
        <div style="font-size:11px; color:var(--muted); margin-top:3px"><?php echo e(round($item['count'] / $total * 100)); ?>% of all evaluations</div>
      </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

      <div style="border-top:1px solid var(--border); padding-top:14px; margin-top:6px">
        <a href="<?php echo e(route('admin.janitors.create')); ?>" class="btn btn-primary" style="width:100%; justify-content:center; margin-bottom:8px">+ Add Janitor</a>
        <a href="<?php echo e(route('admin.users.create')); ?>"    class="btn btn-secondary" style="width:100%; justify-content:center">+ Add Account</a>
      </div>
    </div>
  </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\qmmc_laravel\qmmc_laravel\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>