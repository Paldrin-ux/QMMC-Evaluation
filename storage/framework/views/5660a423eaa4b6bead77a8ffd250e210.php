
<?php $__env->startSection('page-title', 'My Assigned Janitors'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
  <h1>My Assigned Janitors</h1>
  <a href="<?php echo e(route('evaluator.history')); ?>" class="btn btn-secondary">View My Submissions →</a>
</div>

<style>
  .modal-backdrop{
    position:fixed; inset:0; background:rgba(0,0,0,0.35); display:none; z-index:999;
  }
  .modal-backdrop.show{ display:flex; align-items:center; justify-content:center; }
  .modal-card{
    width:min(520px, 92vw);
    background:#fff;
    border:1px solid var(--border);
    border-radius:10px;
    box-shadow:0 16px 40px rgba(0,0,0,0.18);
    padding:16px 16px 14px;
  }
  .modal-title{ font-weight:800; font-size:16px; margin-bottom:6px; }
  .modal-body{ font-size:13px; color:var(--text); margin-bottom:14px; }
  .modal-actions{ display:flex; justify-content:flex-end; gap:10px; }
</style>


<?php if($assignedAreas->isNotEmpty()): ?>
<div style="margin-bottom:18px;padding:12px 16px;background:var(--bg);border:1px solid var(--border);border-radius:8px;font-size:13px">
  <strong style="color:var(--text)">Your assigned areas:</strong>
  <div style="display:flex;flex-wrap:wrap;gap:5px;margin-top:6px">
    <?php $__currentLoopData = $assignedAreas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <span class="badge" style="background:#EEF2FF;color:#3730A3;font-size:11px;padding:4px 10px">
        <?php echo e($area->name); ?>

      </span>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>
  <div style="font-size:11px;color:var(--muted);margin-top:6px">
    All janitors assigned to these areas appear below automatically.
  </div>
</div>
<?php endif; ?>

<?php if($janitors->isEmpty()): ?>
<div class="card">
  <div class="card-body" style="text-align:center;padding:48px;color:var(--muted)">
    <div style="font-size:40px;margin-bottom:12px">📋</div>
    <div style="font-size:15px;font-weight:600;margin-bottom:6px">No janitors found in your areas</div>
    <div style="font-size:13px">
      <?php if($assignedAreas->isEmpty()): ?>
        You have no areas assigned yet. Contact your administrator.
      <?php else: ?>
        No active janitors are assigned to your areas yet.
      <?php endif; ?>
    </div>
  </div>
</div>
<?php else: ?>
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:14px">
  <?php $__currentLoopData = $janitors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $janitor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  <div class="card">
    <div class="card-body">
      <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px">
        <div style="width:44px;height:44px;border-radius:50%;background:var(--navy);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;color:#fff;flex-shrink:0">
          <?php echo e(strtoupper(substr($janitor->name, 0, 2))); ?>

        </div>
        <div>
          <?php $recent = $recentEvaluationByJanitor[$janitor->id] ?? null; ?>
          <div style="font-weight:700;font-size:14px">
            <a
              href="<?php echo e(route('evaluator.evaluate.create', $janitor)); ?>"
              style="text-decoration:none;color:inherit"
              class="janitor-name-link <?php echo e($recent ? 'already-evaluated' : ''); ?>"
              data-already-evaluated="<?php echo e($recent ? '1' : '0'); ?>"
              data-janitor-name="<?php echo e($janitor->name); ?>"
              data-expires-at="<?php echo e($recent ? $recent->created_at->copy()->addHours(24)->format('F d, Y H:i') : ''); ?>"
            >
              <?php echo e($janitor->name); ?>

            </a>
          </div>
          <?php if($janitor->employee_id): ?>
            <div style="font-size:11px;color:var(--muted)">ID: <?php echo e($janitor->employee_id); ?></div>
          <?php endif; ?>
        </div>
      </div>

      
      <div style="margin-bottom:14px">
        <div style="font-size:11px;color:var(--muted);text-transform:uppercase;font-weight:600;margin-bottom:5px">
          Area(s)
        </div>
        <div style="display:flex;flex-wrap:wrap;gap:4px">
          <?php $__currentLoopData = $janitor->areas->whereIn('id', $assignedAreas->pluck('id')); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <span class="badge" style="background:#EEF2FF;color:#3730A3;font-size:10px">
              <?php echo e($area->name); ?>

            </span>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      </div>

      <?php $last = $janitor->evaluations()->latest('eval_date')->first(); ?>
      <?php if($last): ?>
        <div style="font-size:11px;color:var(--muted);margin-bottom:12px">
          Last evaluated: <strong style="color:var(--text)"><?php echo e($last->eval_date->format('M d, Y')); ?></strong>
          · Score: <strong style="color:var(--navy-lt)"><?php echo e($last->total_score); ?>/100</strong>
        </div>
      <?php else: ?>
        <div style="font-size:11px;color:var(--muted);margin-bottom:12px">Not yet evaluated</div>
      <?php endif; ?>

      <a
        href="<?php echo e(route('evaluator.evaluate.create', $janitor)); ?>"
        class="btn btn-primary janitor-evaluate-btn <?php echo e($recent ? 'already-evaluated' : ''); ?>"
        style="width:100%;justify-content:center <?php echo e($recent ? 'opacity:0.65;pointer-events:auto' : ''); ?>"
        data-already-evaluated="<?php echo e($recent ? '1' : '0'); ?>"
        data-janitor-name="<?php echo e($janitor->name); ?>"
        data-expires-at="<?php echo e($recent ? $recent->created_at->copy()->addHours(24)->format('F d, Y H:i') : ''); ?>"
      >
        Evaluate
      </a>
    </div>
  </div>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<?php if($janitors->hasPages()): ?>
<div style="display:flex;justify-content:center;margin-top:20px">
  <?php echo e($janitors->links('vendor.pagination.simple')); ?>

</div>
<?php endif; ?>
<?php endif; ?>


<div class="modal-backdrop" id="already-evaluated-modal-backdrop" role="dialog" aria-modal="true">
  <div class="modal-card">
    <div class="modal-title" id="already-evaluated-modal-title">Already Evaluated</div>
    <div class="modal-body" id="already-evaluated-modal-body">
      You already evaluated this employee within the last 24 hours.
    </div>
    <div class="modal-actions">
      <button type="button" class="btn btn-secondary" id="already-evaluated-modal-close">Close</button>
    </div>
  </div>
</div>

<script>
  (function () {
    const backdrop = document.getElementById('already-evaluated-modal-backdrop');
    const closeBtn = document.getElementById('already-evaluated-modal-close');
    const bodyEl = document.getElementById('already-evaluated-modal-body');
    const titleEl = document.getElementById('already-evaluated-modal-title');

    function showModal({ janitorName, expiresAt }) {
      titleEl.textContent = 'Already Evaluated';
      bodyEl.innerHTML =
        '<div style="margin-bottom:8px"><strong>You already evaluated:</strong> ' + (janitorName || '') + '</div>' +
        '<div>Evaluations are submitted daily, so you can submit again after <strong>' + (expiresAt || '24 hours') + '</strong>.</div>';
      backdrop.classList.add('show');
    }

    function hideModal() {
      backdrop.classList.remove('show');
    }

    closeBtn.addEventListener('click', hideModal);
    backdrop.addEventListener('click', (e) => {
      if (e.target === backdrop) hideModal();
    });

    document.querySelectorAll('.already-evaluated[data-already-evaluated="1"]').forEach(el => {
      el.addEventListener('click', (e) => {
        e.preventDefault();
        showModal({
          janitorName: el.dataset.janitorName,
          expiresAt: el.dataset.expiresAt
        });
      });
    });
  })();
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\qmmc_laravel\qmmc_laravel\resources\views/evaluator/janitors.blade.php ENDPATH**/ ?>