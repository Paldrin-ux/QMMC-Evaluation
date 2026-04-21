
<?php $__env->startSection('page-title', 'Evaluate: ' . $janitor->name); ?>
<?php $__env->startSection('content'); ?>
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: Arial, sans-serif; font-size: 13px; background: #f0f0f0; padding: 20px; color: #222; }
.page { background: white; max-width: 900px; margin: 0 auto; padding: 24px 32px; border: 1px solid #ccc; box-shadow: 0 2px 8px rgba(0,0,0,0.15); }
.header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 14px; }
.header h1 { font-size: 16px; font-weight: bold; letter-spacing: 1px; }
.header h2, .header h3 { font-size: 13px; font-weight: bold; }
.info-row { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 0; border: 1px solid #000; margin-bottom: 8px; }
.info-cell { padding: 4px 8px; border-right: 1px solid #000; }
.info-cell:last-child { border-right: none; }
.info-cell label { font-weight: bold; display: block; font-size: 11px; }
.info-cell input[type="text"],
.info-cell input[type="date"],
.info-cell input[type="time"],
.info-cell select { width: 100%; border: none; border-bottom: 1px solid #666; outline: none; font-size: 13px; padding: 2px 0; background: transparent; }
.direction { font-style: italic; font-size: 11px; margin-bottom: 10px; border: 1px solid #000; padding: 5px 8px; }
.section-header { background: #e8e8e8; padding: 5px 8px; font-weight: bold; font-size: 12px; border: 1px solid #000; border-bottom: none; display: flex; justify-content: space-between; align-items: center; }
.section-header .scoring { font-weight: normal; font-size: 11px; }
table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
th, td { border: 1px solid #000; padding: 5px 8px; vertical-align: middle; }
th { background: #d0d0d0; text-align: center; font-size: 11px; font-weight: bold; }
.criteria-col { width: 42%; }
.check-col { width: 8%; text-align: center; }
.point-col { width: 10%; text-align: center; }
.remarks-col { width: 24%; }
td.criteria-text { font-size: 12px; }
.checkbox-wrap { display: flex; align-items: center; justify-content: center; }
.checkbox-wrap input[type="checkbox"] { width: 16px; height: 16px; cursor: pointer; accent-color: #0066cc; }
.point-display { font-weight: bold; font-size: 14px; text-align: center; min-width: 30px; display: inline-block; }
.point-display.active { color: #007700; }
.point-display.zero { color: #999; }
.total-row td { background: #f0f0f0; font-weight: bold; }
.total-value { font-size: 15px; font-weight: bold; color: #0066cc; text-align: center; }
.overall-row { display: flex; justify-content: flex-end; align-items: center; gap: 12px; padding: 8px; border: 2px solid #000; margin-bottom: 12px; background: #fff8e1; }
.overall-row label { font-weight: bold; font-size: 14px; }
#overall-rating { font-size: 22px; font-weight: bold; color: #cc5500; min-width: 50px; text-align: center; }
.rating-badge { font-size: 13px; padding: 2px 10px; border-radius: 12px; font-weight: bold; }
.rating-badge.excellent { background: #c8e6c9; color: #2e7d32; }
.rating-badge.satisfactory { background: #fff9c4; color: #f57f17; }
.rating-badge.needs-improvement { background: #ffcdd2; color: #c62828; }
.comments-section { margin-bottom: 14px; }
.comments-section label { font-weight: bold; display: block; margin-bottom: 4px; font-size: 12px; }
.comments-section textarea { width: 100%; border: 1px solid #999; padding: 6px; font-size: 12px; resize: vertical; font-family: Arial, sans-serif; }

/* ── REMARKS TEXTAREA inside table cell ── */
.remarks-input {
  width: 100%;
  border: none;
  border-bottom: 1px dashed #aaa;
  outline: none;
  font-size: 11px;
  font-family: Arial, sans-serif;
  resize: none;
  background: transparent;
  padding: 2px 0;
  min-height: 36px;
  line-height: 1.4;
  color: #333;
}
.remarks-input:focus {
  border-bottom-color: #0066cc;
  background: #f8f8ff;
}

/* ── SIGNATURE PAD ── */
.sig-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 16px; }
.sig-block label { font-weight: bold; font-size: 12px; display: block; margin-bottom: 6px; }

.sig-pad-wrap {
  position: relative;
  border: 1px solid #999;
  border-radius: 4px;
  background: #fafafa;
  overflow: hidden;
}
.sig-pad-wrap canvas {
  display: block;
  width: 100%;
  height: 90px;
  cursor: crosshair;
  touch-action: none;
}
.sig-pad-controls {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 3px 6px;
  background: #f0f0f0;
  border-top: 1px solid #ddd;
}
.sig-pad-hint {
  font-size: 10px;
  color: #888;
  font-style: italic;
}
.sig-clear-btn {
  font-size: 10px;
  color: #c00;
  background: none;
  border: none;
  cursor: pointer;
  padding: 2px 6px;
  border-radius: 3px;
  font-weight: bold;
}
.sig-clear-btn:hover { background: #fdd; }

/* Signature required — error state */
.sig-pad-wrap.sig-error {
  border: 2px solid #cc0000;
  box-shadow: 0 0 0 3px rgba(204,0,0,0.15);
}
.sig-error-msg {
  display: none;
  color: #cc0000;
  font-size: 11px;
  font-weight: bold;
  margin-top: 5px;
}
.sig-error-msg.visible { display: block; }

/* Submit button disabled look */
.btn-submit:disabled,
.btn-submit.btn-disabled {
  background: #999 !important;
  cursor: not-allowed;
}

/* Hidden input to store base64 data */
.sig-data-input { display: none; }

.sig-name-input {
  width: 100%;
  border: none;
  border-bottom: 1px solid #333;
  outline: none;
  font-size: 13px;
  padding: 3px 0;
  margin-top: 6px;
  background: transparent;
}
.sig-label { font-style: italic; font-size: 11px; text-align: center; margin-top: 4px; color: #555; }

.submit-row { text-align: center; margin-top: 20px; }
.btn-submit { background: #003087; color: white; border: none; padding: 10px 40px; font-size: 14px; font-weight: bold; cursor: pointer; border-radius: 4px; letter-spacing: 1px; }
.btn-submit:hover { background: #004abf; }
.btn-link { display: inline-block; margin-top: 10px; color: #003087; font-size: 12px; text-decoration: none; }
.btn-link:hover { text-decoration: underline; }
.doc-footer { text-align: right; font-size: 10px; color: #888; margin-top: 10px; border-top: 1px solid #ccc; padding-top: 4px; }
.alert { padding: 8px 12px; background: #d4edda; border: 1px solid #c3e6cb; color: #155724; border-radius: 3px; margin-bottom: 12px; }
.error-text { color: red; font-size: 11px; display: block; margin-top: 2px; }

@media print {
  body { background: white; padding: 0; }
  .page { box-shadow: none; border: none; }
  .btn-submit, .btn-link, .sig-clear-btn, .sig-pad-hint { display: none; }
  .sig-pad-wrap { border: 1px solid #ccc; }
  .sig-pad-controls { display: none; }
  .remarks-input { border-bottom: 1px solid #ccc; }
}
</style>

<?php
  $sectionA = $sections['A'] ?? [];
  $sectionB = $sections['B'] ?? [];
  $sectionC = $sections['C'] ?? [];
?>

<div class="page">
  <div class="header">
    <h1>QUIRINO MEMORIAL MEDICAL CENTER</h1>
    <h2>GENERAL SERVICES SECTION</h2>
    <h3>JANITORIAL STAFF PERFORMANCE AND AREA QUALITY EVALUATION</h3>
  </div>

  <form method="POST" action="<?php echo e(route('evaluator.evaluate.store', $janitor)); ?>" id="evalForm">
    <?php echo csrf_field(); ?>

    <div class="info-row">
      <div class="info-cell">
        <label>JANITOR'S NAME:</label>
        <input type="text" name="janitor_name" required value="<?php echo e(old('janitor_name', $janitor->name)); ?>">
        <?php $__errorArgs = ['janitor_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="error-text"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>
      <div class="info-cell">
        <label>AREA:</label>
        <select name="area_id" required>
          <?php $__currentLoopData = $areas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($a->id); ?>" <?php echo e((string) old('area_id', $areas->first()->id) === (string) $a->id ? 'selected' : ''); ?>><?php echo e($a->name); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <?php $__errorArgs = ['area_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="error-text"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>
      <div class="info-cell">
        <label>DATE:</label>
        <input type="date" name="eval_date" required value="<?php echo e(old('eval_date', now()->format('Y-m-d'))); ?>">
        <?php $__errorArgs = ['eval_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="error-text"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>
      <div class="info-cell">
        <label>TIME:</label>
        <input type="time" name="eval_time" required value="<?php echo e(old('eval_time', now()->format('H:i'))); ?>">
        <?php $__errorArgs = ['eval_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="error-text"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>
    </div>

    <div class="direction">
      <strong>Direction:</strong> This evaluation must be conducted by the Clinical Supervisor / Senior Nurse on a Daily basis.
      Head of the Housekeeping Unit or representative may also conduct a spot check on every area to validate the result of this evaluation.
    </div>

    
    <div class="section-header">
      <span>A. &nbsp; CLEANLINESS AND ORDERLINESS (50%)</span>
      <span class="scoring">&nbsp;&nbsp; <strong>5</strong> Compliant &ndash; Criteria are fully met &nbsp;|&nbsp; <strong>0</strong> Non-Compliant &ndash; Criteria are not met or need improvement.</span>
    </div>
    <table>
      <thead>
        <tr>
          <th class="criteria-col">CRITERIA</th>
          <th class="check-col">YES</th>
          <th class="check-col">NO</th>
          <th class="point-col">POINT VALUE</th>
          <th class="remarks-col">REMARKS</th>
        </tr>
      </thead>
      <tbody>
        <?php $__currentLoopData = $sectionA; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
          <td class="criteria-text"><?php echo e($item['label']); ?></td>
          <td class="check-col">
            <div class="checkbox-wrap">
              <input type="checkbox"
                     name="<?php echo e($item['field']); ?>"
                     id="yes_<?php echo e($item['field']); ?>"
                     data-points="<?php echo e($item['pts']); ?>"
                     <?php echo e(old($item['field']) ? 'checked' : ''); ?>

                     onchange="handleYes(this)">
            </div>
          </td>
          <td class="check-col">
            <div class="checkbox-wrap">
              <input type="checkbox"
                     id="no_<?php echo e($item['field']); ?>"
                     data-pair="<?php echo e($item['field']); ?>"
                     <?php echo e(old($item['field']) ? '' : 'checked'); ?>

                     onchange="handleNo(this)">
            </div>
          </td>
          <td class="point-col">
            <span class="point-display <?php echo e(old($item['field']) ? 'active' : 'zero'); ?>"
                  id="pt_<?php echo e($item['field']); ?>">
              <?php echo e(old($item['field']) ? $item['pts'] : 0); ?>

            </span>
          </td>
          <td class="remarks-col">
            <textarea
              class="remarks-input"
              name="remarks_<?php echo e($item['field']); ?>"
              rows="2"
              placeholder="Remarks…"><?php echo e(old('remarks_' . $item['field'])); ?></textarea>
          </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </tbody>
      <tfoot>
        <tr class="total-row">
          <td colspan="3" style="text-align:right; font-size:12px;">TOTAL POINTS (MAX. 50)</td>
          <td class="total-value" id="total_a">0</td>
          <td></td>
        </tr>
      </tfoot>
    </table>

    
    <div class="section-header">
      <span>B. &nbsp; OVERALL PRACTICES AND BEHAVIOR (30%)</span>
      <span class="scoring">&nbsp;&nbsp; <strong>5</strong> Compliant &ndash; Criteria are fully met &nbsp;|&nbsp; <strong>0</strong> Non-Compliant &ndash; Criteria are not met or need improvement.</span>
    </div>
    <table>
      <thead>
        <tr>
          <th class="criteria-col">CRITERIA</th>
          <th class="check-col">YES</th>
          <th class="check-col">NO</th>
          <th class="point-col">POINT VALUE</th>
          <th class="remarks-col">REMARKS</th>
        </tr>
      </thead>
      <tbody>
        <?php $__currentLoopData = $sectionB; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
          <td class="criteria-text"><?php echo e($item['label']); ?></td>
          <td class="check-col">
            <div class="checkbox-wrap">
              <input type="checkbox"
                     name="<?php echo e($item['field']); ?>"
                     id="yes_<?php echo e($item['field']); ?>"
                     data-points="<?php echo e($item['pts']); ?>"
                     <?php echo e(old($item['field']) ? 'checked' : ''); ?>

                     onchange="handleYes(this)">
            </div>
          </td>
          <td class="check-col">
            <div class="checkbox-wrap">
              <input type="checkbox"
                     id="no_<?php echo e($item['field']); ?>"
                     data-pair="<?php echo e($item['field']); ?>"
                     <?php echo e(old($item['field']) ? '' : 'checked'); ?>

                     onchange="handleNo(this)">
            </div>
          </td>
          <td class="point-col">
            <span class="point-display <?php echo e(old($item['field']) ? 'active' : 'zero'); ?>"
                  id="pt_<?php echo e($item['field']); ?>">
              <?php echo e(old($item['field']) ? $item['pts'] : 0); ?>

            </span>
          </td>
          <td class="remarks-col">
            <textarea
              class="remarks-input"
              name="remarks_<?php echo e($item['field']); ?>"
              rows="2"
              placeholder="Remarks…"><?php echo e(old('remarks_' . $item['field'])); ?></textarea>
          </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </tbody>
      <tfoot>
        <tr class="total-row">
          <td colspan="3" style="text-align:right; font-size:12px;">TOTAL POINTS (MAX. 30)</td>
          <td class="total-value" id="total_b">0</td>
          <td></td>
        </tr>
      </tfoot>
    </table>

    
    <div class="section-header">
      <span>C. &nbsp; COMPLIANCE WITH REGULATORY REQUIREMENT (20%)</span>
      <span class="scoring">&nbsp;&nbsp; <strong>10</strong> Compliant &ndash; Criteria are fully met &nbsp;|&nbsp; <strong>0</strong> Non-Compliant &ndash; Criteria are not met or need improvement.</span>
    </div>
    <table>
      <thead>
        <tr>
          <th class="criteria-col">CRITERIA</th>
          <th class="check-col">YES</th>
          <th class="check-col">NO</th>
          <th class="point-col">POINT VALUE</th>
          <th class="remarks-col">REMARKS</th>
        </tr>
      </thead>
      <tbody>
        <?php $__currentLoopData = $sectionC; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
          <td class="criteria-text"><?php echo e($item['label']); ?></td>
          <td class="check-col">
            <div class="checkbox-wrap">
              <input type="checkbox"
                     name="<?php echo e($item['field']); ?>"
                     id="yes_<?php echo e($item['field']); ?>"
                     data-points="<?php echo e($item['pts']); ?>"
                     <?php echo e(old($item['field']) ? 'checked' : ''); ?>

                     onchange="handleYes(this)">
            </div>
          </td>
          <td class="check-col">
            <div class="checkbox-wrap">
              <input type="checkbox"
                     id="no_<?php echo e($item['field']); ?>"
                     data-pair="<?php echo e($item['field']); ?>"
                     <?php echo e(old($item['field']) ? '' : 'checked'); ?>

                     onchange="handleNo(this)">
            </div>
          </td>
          <td class="point-col">
            <span class="point-display <?php echo e(old($item['field']) ? 'active' : 'zero'); ?>"
                  id="pt_<?php echo e($item['field']); ?>">
              <?php echo e(old($item['field']) ? $item['pts'] : 0); ?>

            </span>
          </td>
          <td class="remarks-col">
            <textarea
              class="remarks-input"
              name="remarks_<?php echo e($item['field']); ?>"
              rows="2"
              placeholder="Remarks…"><?php echo e(old('remarks_' . $item['field'])); ?></textarea>
          </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </tbody>
      <tfoot>
        <tr class="total-row">
          <td colspan="3" style="text-align:right; font-size:12px;">TOTAL POINTS (MAX. 20)</td>
          <td class="total-value" id="total_c">0</td>
          <td></td>
        </tr>
      </tfoot>
    </table>

    <div class="overall-row">
      <label>OVERALL RATING (A+B+C):</label>
      <span id="overall-rating">0</span>
      <span id="rating-badge" class="rating-badge needs-improvement">Needs Improvement</span>
    </div>

    <div class="comments-section">
      <label>Comments/Suggestions for Improvement:</label>
      <textarea name="comments" rows="4" placeholder="Enter comments or suggestions here..."><?php echo e(old('comments')); ?></textarea>
    </div>

    
    <div class="sig-row">

      
      <div class="sig-block">
        <label>Evaluated by:</label>
        <div class="sig-pad-wrap">
          <canvas id="canvas_evaluated" data-target="sig_evaluated"></canvas>
          <div class="sig-pad-controls">
            <span class="sig-pad-hint">✍️ Sign with mouse or touch</span>
            <button type="button" class="sig-clear-btn" onclick="clearPad('canvas_evaluated', 'sig_evaluated')">✕ Clear</button>
          </div>
        </div>
        <input type="hidden" name="sig_evaluated" id="sig_evaluated" value="<?php echo e(old('sig_evaluated')); ?>">
        <input type="text"
               class="sig-name-input"
               name="evaluated_by"
               value="<?php echo e(old('evaluated_by')); ?>"
               placeholder="Print name here">
        <div class="sig-label">Signature over printed name</div>
      </div>

      
      <div class="sig-block">
        <label>Noted by:</label>
        <div class="sig-pad-wrap">
          <canvas id="canvas_noted" data-target="sig_noted"></canvas>
          <div class="sig-pad-controls">
            <span class="sig-pad-hint">✍️ Sign with mouse or touch</span>
            <button type="button" class="sig-clear-btn" onclick="clearPad('canvas_noted', 'sig_noted')">✕ Clear</button>
          </div>
        </div>
        <input type="hidden" name="sig_noted" id="sig_noted" value="<?php echo e(old('sig_noted')); ?>">
        <input type="text"
               class="sig-name-input"
               name="noted_by"
               value="<?php echo e(old('noted_by')); ?>"
               placeholder="Print name here">
        <div class="sig-label">Signature over printed name</div>
      </div>

    </div>

    <div class="submit-row">
      <button type="submit" class="btn-submit">SUBMIT EVALUATION</button>
      <br>
      <a href="<?php echo e(route('evaluator.history')); ?>" class="btn-link">View All Records &rarr;</a>
    </div>
  </form>

  <div class="doc-footer">GSU - 005 &nbsp;|&nbsp; Rev 1 09 January 2026</div>
</div>

<script>
/* ══════════════════════════════════════════
   SCORING LOGIC
══════════════════════════════════════════ */
const sections = {
  a: ['a1','a2','a3','a4','a5','a6','a7','a8','a9','a10'],
  b: ['b1','b2','b3','b4','b5','b6'],
  c: ['c1','c2']
};

function handleYes(yesCheckbox) {
  const fieldName = yesCheckbox.name;
  const pts       = parseInt(yesCheckbox.dataset.points);
  const ptSpan    = document.getElementById('pt_' + fieldName);
  const noBox     = document.getElementById('no_' + fieldName);
  if (yesCheckbox.checked) {
    ptSpan.textContent = pts;
    ptSpan.className   = 'point-display active';
    if (noBox) noBox.checked = false;
  } else {
    ptSpan.textContent = '0';
    ptSpan.className   = 'point-display zero';
    if (noBox) noBox.checked = true;
  }
  recalcTotals();
}

function handleNo(noCheckbox) {
  const fieldName = noCheckbox.dataset.pair;
  const yesBox    = document.getElementById('yes_' + fieldName);
  const ptSpan    = document.getElementById('pt_' + fieldName);
  const pts       = parseInt(yesBox.dataset.points);
  if (noCheckbox.checked) {
    yesBox.checked     = false;
    ptSpan.textContent = '0';
    ptSpan.className   = 'point-display zero';
  } else {
    yesBox.checked     = true;
    ptSpan.textContent = pts;
    ptSpan.className   = 'point-display active';
  }
  recalcTotals();
}

function recalcTotals() {
  let totalA = 0, totalB = 0, totalC = 0;
  sections.a.forEach(name => { const cb = document.getElementById('yes_' + name); if (cb && cb.checked) totalA += 5; });
  sections.b.forEach(name => { const cb = document.getElementById('yes_' + name); if (cb && cb.checked) totalB += 5; });
  sections.c.forEach(name => { const cb = document.getElementById('yes_' + name); if (cb && cb.checked) totalC += 10; });
  document.getElementById('total_a').textContent = totalA;
  document.getElementById('total_b').textContent = totalB;
  document.getElementById('total_c').textContent = totalC;
  const overall = totalA + totalB + totalC;
  document.getElementById('overall-rating').textContent = overall;
  const badge = document.getElementById('rating-badge');
  if (overall >= 90)      { badge.textContent = 'Excellent';         badge.className = 'rating-badge excellent'; }
  else if (overall >= 70) { badge.textContent = 'Satisfactory';      badge.className = 'rating-badge satisfactory'; }
  else                    { badge.textContent = 'Needs Improvement';  badge.className = 'rating-badge needs-improvement'; }
}

document.addEventListener('DOMContentLoaded', recalcTotals);


/* ══════════════════════════════════════════
   SIGNATURE PAD
══════════════════════════════════════════ */
class SignaturePad {
  constructor(canvasId, hiddenInputId) {
    this.canvas      = document.getElementById(canvasId);
    this.hiddenInput = document.getElementById(hiddenInputId);
    this.ctx         = this.canvas.getContext('2d');
    this.drawing     = false;
    this.isEmpty     = true;

    this._setupCanvas();
    this._bindEvents();

    // Restore saved signature if present (e.g. after validation failure)
    if (this.hiddenInput.value) {
      const img = new Image();
      img.onload = () => this.ctx.drawImage(img, 0, 0);
      img.src = this.hiddenInput.value;
      this.isEmpty = false;
    }
  }

  _setupCanvas() {
    // Make canvas resolution match its CSS display size (prevents blur)
    const rect = this.canvas.getBoundingClientRect();
    const dpr  = window.devicePixelRatio || 1;
    this.canvas.width  = (rect.width  || 380) * dpr;
    this.canvas.height = (rect.height || 90)  * dpr;
    this.ctx.scale(dpr, dpr);
    this.ctx.strokeStyle = '#111';
    this.ctx.lineWidth   = 2;
    this.ctx.lineCap     = 'round';
    this.ctx.lineJoin    = 'round';
  }

  _getPos(e) {
    const rect = this.canvas.getBoundingClientRect();
    const src  = e.touches ? e.touches[0] : e;
    return {
      x: src.clientX - rect.left,
      y: src.clientY - rect.top
    };
  }

  _bindEvents() {
    // Mouse
    this.canvas.addEventListener('mousedown',  e => this._start(e));
    this.canvas.addEventListener('mousemove',  e => this._move(e));
    this.canvas.addEventListener('mouseup',    e => this._end());
    this.canvas.addEventListener('mouseleave', e => this._end());
    // Touch
    this.canvas.addEventListener('touchstart', e => { e.preventDefault(); this._start(e); }, { passive: false });
    this.canvas.addEventListener('touchmove',  e => { e.preventDefault(); this._move(e);  }, { passive: false });
    this.canvas.addEventListener('touchend',   e => this._end());
  }

  _start(e) {
    this.drawing = true;
    this.isEmpty = false;
    const pos = this._getPos(e);
    this.ctx.beginPath();
    this.ctx.moveTo(pos.x, pos.y);
  }

  _move(e) {
    if (!this.drawing) return;
    const pos = this._getPos(e);
    this.ctx.lineTo(pos.x, pos.y);
    this.ctx.stroke();
  }

  _end() {
    if (!this.drawing) return;
    this.drawing = false;
    // Save to hidden input as base64 PNG
    this.hiddenInput.value = this.canvas.toDataURL('image/png');
  }

  clear() {
    const w = this.canvas.width  / (window.devicePixelRatio || 1);
    const h = this.canvas.height / (window.devicePixelRatio || 1);
    this.ctx.clearRect(0, 0, w, h);
    this.hiddenInput.value = '';
    this.isEmpty = true;
  }
}

// Global registry
const pads = {};

function clearPad(canvasId, inputId) {
  if (pads[canvasId]) pads[canvasId].clear();
}

document.addEventListener('DOMContentLoaded', () => {
  pads['canvas_evaluated'] = new SignaturePad('canvas_evaluated', 'sig_evaluated');
  pads['canvas_noted']     = new SignaturePad('canvas_noted',     'sig_noted');
});

// ── SUBMIT VALIDATION: block if any signature is missing ──────────
document.getElementById('evalForm').addEventListener('submit', function(e) {
  let valid = true;

  // Check evaluated-by signature
  const padEval  = pads['canvas_evaluated'];
  const wrapEval = document.getElementById('canvas_evaluated').closest('.sig-pad-wrap');
  const errEval  = document.getElementById('err_evaluated');

  if (padEval.isEmpty) {
    valid = false;
    wrapEval.classList.add('sig-error');
    errEval.classList.add('visible');
    // Shake animation
    wrapEval.animate([
      { transform: 'translateX(0)' },
      { transform: 'translateX(-6px)' },
      { transform: 'translateX(6px)' },
      { transform: 'translateX(-4px)' },
      { transform: 'translateX(4px)' },
      { transform: 'translateX(0)' }
    ], { duration: 350, easing: 'ease-in-out' });
  } else {
    wrapEval.classList.remove('sig-error');
    errEval.classList.remove('visible');
    padEval.hiddenInput.value = padEval.canvas.toDataURL('image/png');
  }

  // Check noted-by signature
  const padNoted  = pads['canvas_noted'];
  const wrapNoted = document.getElementById('canvas_noted').closest('.sig-pad-wrap');
  const errNoted  = document.getElementById('err_noted');

  if (padNoted.isEmpty) {
    valid = false;
    wrapNoted.classList.add('sig-error');
    errNoted.classList.add('visible');
    wrapNoted.animate([
      { transform: 'translateX(0)' },
      { transform: 'translateX(-6px)' },
      { transform: 'translateX(6px)' },
      { transform: 'translateX(-4px)' },
      { transform: 'translateX(4px)' },
      { transform: 'translateX(0)' }
    ], { duration: 350, easing: 'ease-in-out' });
  } else {
    wrapNoted.classList.remove('sig-error');
    errNoted.classList.remove('visible');
    padNoted.hiddenInput.value = padNoted.canvas.toDataURL('image/png');
  }

  if (!valid) {
    e.preventDefault(); // STOP the form from submitting
    // Scroll to the signature section so the user sees the error
    document.querySelector('.sig-row').scrollIntoView({ behavior: 'smooth', block: 'center' });
  }
});

// Clear error highlight as soon as the user starts signing
document.addEventListener('DOMContentLoaded', () => {
  ['canvas_evaluated', 'canvas_noted'].forEach(canvasId => {
    const canvas = document.getElementById(canvasId);
    const wrap   = canvas.closest('.sig-pad-wrap');
    const errId  = canvasId === 'canvas_evaluated' ? 'err_evaluated' : 'err_noted';
    const errEl  = document.getElementById(errId);
    ['mousedown', 'touchstart'].forEach(evt => {
      canvas.addEventListener(evt, () => {
        wrap.classList.remove('sig-error');
        errEl.classList.remove('visible');
      }, { once: false });
    });
  });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\qmmc_laravel\qmmc_laravel\resources\views/evaluator/form.blade.php ENDPATH**/ ?>