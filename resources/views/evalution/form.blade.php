<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>QMMC - Janitorial Staff Performance Evaluation</title>
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
.info-cell input[type="text"], .info-cell input[type="date"], .info-cell input[type="time"] { width: 100%; border: none; border-bottom: 1px solid #666; outline: none; font-size: 13px; padding: 2px 0; background: transparent; }
.direction { font-style: italic; font-size: 11px; margin-bottom: 10px; border: 1px solid #000; padding: 5px 8px; }
.section-header { background: #e8e8e8; padding: 5px 8px; font-weight: bold; font-size: 12px; border: 1px solid #000; border-bottom: none; display: flex; justify-content: space-between; align-items: center; }
.section-header .scoring { font-weight: normal; font-size: 11px; }
table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
th, td { border: 1px solid #000; padding: 5px 8px; vertical-align: middle; }
th { background: #d0d0d0; text-align: center; font-size: 11px; font-weight: bold; }
.criteria-col { width: 50%; } .check-col { width: 9%; text-align: center; } .point-col { width: 12%; text-align: center; } .remarks-col { width: 20%; }
td.criteria-text { font-size: 12px; }
.checkbox-wrap { display: flex; align-items: center; justify-content: center; }
.checkbox-wrap input[type="checkbox"] { width: 16px; height: 16px; cursor: pointer; accent-color: #0066cc; }
.point-display { font-weight: bold; font-size: 14px; text-align: center; min-width: 30px; display: inline-block; }
.point-display.active { color: #007700; } .point-display.zero { color: #999; }
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
.sig-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 16px; }
.sig-block label { font-weight: bold; font-size: 12px; display: block; margin-bottom: 4px; }
.sig-block input[type="text"] { width: 100%; border: none; border-bottom: 1px solid #333; outline: none; font-size: 13px; padding: 3px 0; }
.sig-label { font-style: italic; font-size: 11px; text-align: center; margin-top: 4px; color: #555; }
.submit-row { text-align: center; margin-top: 20px; }
.btn-submit { background: #003087; color: white; border: none; padding: 10px 40px; font-size: 14px; font-weight: bold; cursor: pointer; border-radius: 4px; letter-spacing: 1px; }
.btn-submit:hover { background: #004abf; }
.btn-link { display: inline-block; margin-top: 10px; color: #003087; font-size: 12px; text-decoration: none; }
.btn-link:hover { text-decoration: underline; }
.doc-footer { text-align: right; font-size: 10px; color: #888; margin-top: 10px; border-top: 1px solid #ccc; padding-top: 4px; }
.alert { padding: 8px 12px; background: #d4edda; border: 1px solid #c3e6cb; color: #155724; border-radius: 3px; margin-bottom: 12px; }
.error-text { color: red; font-size: 11px; display: block; margin-top: 2px; }
@media print { body { background: white; padding: 0; } .page { box-shadow: none; border: none; } .btn-submit, .btn-link { display: none; } }
</style>
</head>
<body>
<div class="page">

  <div class="header">
    <h1>QUIRINO MEMORIAL MEDICAL CENTER</h1>
    <h2>GENERAL SERVICES SECTION</h2>
    <h3>JANITORIAL STAFF PERFORMANCE AND AREA QUALITY EVALUATION</h3>
  </div>

  @if(session('success'))
    <div class="alert">{{ session('success') }}</div>
  @endif

  <form method="POST" action="{{ route('evaluation.store') }}">
    @csrf

    <div class="info-row">
      <div class="info-cell">
        <label>JANITOR'S NAME:</label>
        <input type="text" name="janitor_name" required value="{{ old('janitor_name') }}">
        @error('janitor_name')<span class="error-text">{{ $message }}</span>@enderror
      </div>
      <div class="info-cell">
        <label>AREA:</label>
        <input type="text" name="area" required value="{{ old('area') }}">
        @error('area')<span class="error-text">{{ $message }}</span>@enderror
      </div>
      <div class="info-cell">
        <label>DATE:</label>
        <input type="date" name="date" required value="{{ old('date') }}">
        @error('date')<span class="error-text">{{ $message }}</span>@enderror
      </div>
      <div class="info-cell">
        <label>TIME:</label>
        <input type="time" name="time" required value="{{ old('time') }}">
        @error('time')<span class="error-text">{{ $message }}</span>@enderror
      </div>
    </div>

    <div class="direction">
      <strong>Direction:</strong> This evaluation must be conducted by the Clinical Supervisor / Senior Nurse on a Daily basis.
      Head of the Housekeeping Unit or representative may also conduct a spot check on every area to validate the result of this evaluation.
    </div>

    {{-- SECTION A --}}
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
        @foreach($sectionA as $item)
        <tr>
          <td class="criteria-text">{{ $item['label'] }}</td>
          <td class="check-col">
            <div class="checkbox-wrap">
              <input type="checkbox"
                     name="{{ $item['field'] }}"
                     id="yes_{{ $item['field'] }}"
                     data-points="{{ $item['pts'] }}"
                     {{ old($item['field']) ? 'checked' : '' }}
                     onchange="handleYes(this)">
            </div>
          </td>
          <td class="check-col">
            <div class="checkbox-wrap">
              <input type="checkbox"
                     id="no_{{ $item['field'] }}"
                     data-pair="{{ $item['field'] }}"
                     {{ old($item['field']) ? '' : 'checked' }}
                     onchange="handleNo(this)">
            </div>
          </td>
          <td class="point-col">
            <span class="point-display {{ old($item['field']) ? 'active' : 'zero' }}"
                  id="pt_{{ $item['field'] }}">
              {{ old($item['field']) ? $item['pts'] : 0 }}
            </span>
          </td>
          <td class="remarks-col"></td>
        </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr class="total-row">
          <td colspan="3" style="text-align:right; font-size:12px;">TOTAL POINTS (MAX. 50)</td>
          <td class="total-value" id="total_a">0</td>
          <td></td>
        </tr>
      </tfoot>
    </table>

    {{-- SECTION B --}}
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
        @foreach($sectionB as $item)
        <tr>
          <td class="criteria-text">{{ $item['label'] }}</td>
          <td class="check-col">
            <div class="checkbox-wrap">
              <input type="checkbox"
                     name="{{ $item['field'] }}"
                     id="yes_{{ $item['field'] }}"
                     data-points="{{ $item['pts'] }}"
                     {{ old($item['field']) ? 'checked' : '' }}
                     onchange="handleYes(this)">
            </div>
          </td>
          <td class="check-col">
            <div class="checkbox-wrap">
              <input type="checkbox"
                     id="no_{{ $item['field'] }}"
                     data-pair="{{ $item['field'] }}"
                     {{ old($item['field']) ? '' : 'checked' }}
                     onchange="handleNo(this)">
            </div>
          </td>
          <td class="point-col">
            <span class="point-display {{ old($item['field']) ? 'active' : 'zero' }}"
                  id="pt_{{ $item['field'] }}">
              {{ old($item['field']) ? $item['pts'] : 0 }}
            </span>
          </td>
          <td class="remarks-col"></td>
        </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr class="total-row">
          <td colspan="3" style="text-align:right; font-size:12px;">TOTAL POINTS (MAX. 30)</td>
          <td class="total-value" id="total_b">0</td>
          <td></td>
        </tr>
      </tfoot>
    </table>

    {{-- SECTION C --}}
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
        @foreach($sectionC as $item)
        <tr>
          <td class="criteria-text">{{ $item['label'] }}</td>
          <td class="check-col">
            <div class="checkbox-wrap">
              <input type="checkbox"
                     name="{{ $item['field'] }}"
                     id="yes_{{ $item['field'] }}"
                     data-points="{{ $item['pts'] }}"
                     {{ old($item['field']) ? 'checked' : '' }}
                     onchange="handleYes(this)">
            </div>
          </td>
          <td class="check-col">
            <div class="checkbox-wrap">
              <input type="checkbox"
                     id="no_{{ $item['field'] }}"
                     data-pair="{{ $item['field'] }}"
                     {{ old($item['field']) ? '' : 'checked' }}
                     onchange="handleNo(this)">
            </div>
          </td>
          <td class="point-col">
            <span class="point-display {{ old($item['field']) ? 'active' : 'zero' }}"
                  id="pt_{{ $item['field'] }}">
              {{ old($item['field']) ? $item['pts'] : 0 }}
            </span>
          </td>
          <td class="remarks-col"></td>
        </tr>
        @endforeach
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
      <textarea name="comments" rows="4" placeholder="Enter comments or suggestions here...">{{ old('comments') }}</textarea>
    </div>

    <div class="sig-row">
      <div class="sig-block">
        <label>Evaluated by:</label>
        <input type="text" name="evaluated_by" value="{{ old('evaluated_by') }}" placeholder="Name and Signature">
        <div class="sig-label">Signature over printed name</div>
      </div>
      <div class="sig-block">
        <label>Noted by:</label>
        <input type="text" name="noted_by" value="{{ old('noted_by') }}" placeholder="Name and Signature">
        <div class="sig-label">Signature over printed name</div>
      </div>
    </div>

    <div class="submit-row">
      <button type="submit" class="btn-submit">SUBMIT EVALUATION</button>
      <br>
      <a href="{{ route('evaluation.list') }}" class="btn-link">View All Records &rarr;</a>
    </div>
  </form>

  <div class="doc-footer">GSU - 005 &nbsp;|&nbsp; Rev 1 09 January 2026</div>
</div>

<script>
const sections = {
  a: ['a1','a2','a3','a4','a5','a6','a7','a8','a9','a10'],
  b: ['b1','b2','b3','b4','b5','b6'],
  c: ['c1','c2']
};

function handleYes(yesCheckbox) {
  const fieldName = yesCheckbox.name;
  const pts = parseInt(yesCheckbox.dataset.points);
  const ptSpan = document.getElementById('pt_' + fieldName);
  const noBox  = document.getElementById('no_' + fieldName);

  if (yesCheckbox.checked) {
    ptSpan.textContent = pts;
    ptSpan.className = 'point-display active';
    if (noBox) noBox.checked = false;
  } else {
    ptSpan.textContent = '0';
    ptSpan.className = 'point-display zero';
    if (noBox) noBox.checked = true;
  }
  recalcTotals();
}

function handleNo(noCheckbox) {
  const fieldName = noCheckbox.dataset.pair;
  const yesBox = document.getElementById('yes_' + fieldName);
  const ptSpan = document.getElementById('pt_' + fieldName);
  const pts = parseInt(yesBox.dataset.points);

  if (noCheckbox.checked) {
    yesBox.checked = false;
    ptSpan.textContent = '0';
    ptSpan.className = 'point-display zero';
  } else {
    yesBox.checked = true;
    ptSpan.textContent = pts;
    ptSpan.className = 'point-display active';
  }
  recalcTotals();
}

function recalcTotals() {
  let totalA = 0, totalB = 0, totalC = 0;

  sections.a.forEach(name => {
    const cb = document.getElementById('yes_' + name);
    if (cb && cb.checked) totalA += 5;
  });
  sections.b.forEach(name => {
    const cb = document.getElementById('yes_' + name);
    if (cb && cb.checked) totalB += 5;
  });
  sections.c.forEach(name => {
    const cb = document.getElementById('yes_' + name);
    if (cb && cb.checked) totalC += 10;
  });

  document.getElementById('total_a').textContent = totalA;
  document.getElementById('total_b').textContent = totalB;
  document.getElementById('total_c').textContent = totalC;

  const overall = totalA + totalB + totalC;
  document.getElementById('overall-rating').textContent = overall;

  const badge = document.getElementById('rating-badge');
  if (overall >= 90) {
    badge.textContent = 'Excellent';
    badge.className = 'rating-badge excellent';
  } else if (overall >= 70) {
    badge.textContent = 'Satisfactory';
    badge.className = 'rating-badge satisfactory';
  } else {
    badge.textContent = 'Needs Improvement';
    badge.className = 'rating-badge needs-improvement';
  }
}

document.addEventListener('DOMContentLoaded', recalcTotals);
</script>
</body>
</html>
