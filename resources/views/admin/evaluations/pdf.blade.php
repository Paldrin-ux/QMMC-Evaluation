<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 11px; color: #1A2340; }
.page { padding: 28px 32px; }

/* Header */
.header { text-align: center; border-bottom: 2px solid #002060; padding-bottom: 10px; margin-bottom: 14px; }
.header h1 { font-size: 13px; font-weight: bold; letter-spacing: .5px; }
.header h2 { font-size: 11px; font-weight: normal; margin-top: 2px; }
.header h3 { font-size: 11px; margin-top: 2px; }

/* Info grid */
.info-grid { display: table; width: 100%; border: 1px solid #ccc; margin-bottom: 10px; }
.info-row  { display: table-row; }
.info-cell { display: table-cell; padding: 5px 8px; border-right: 1px solid #ccc; font-size: 10px; }
.info-cell:last-child { border-right: none; }
.info-cell strong { display: block; font-size: 9px; color: #6B7494; margin-bottom: 2px; text-transform: uppercase; }

/* Direction */
.direction { font-style: italic; font-size: 10px; border: 1px solid #ccc; padding: 5px 8px; margin-bottom: 10px; }

/* Section header */
.section-hdr { background: #E8EAEE; padding: 5px 8px; font-weight: bold; font-size: 10px; border: 1px solid #ccc; border-bottom: none; }

/* Table */
table { width: 100%; border-collapse: collapse; margin-bottom: 12px; font-size: 10px; }
th, td { border: 1px solid #ccc; padding: 5px 7px; vertical-align: middle; }
th { background: #D4D8E0; font-weight: bold; text-align: center; font-size: 9px; }
.crit-col  { width: 52%; }
.check-col { width: 8%;  text-align: center; }
.pts-col   { width: 10%; text-align: center; }
.rem-col   { width: 22%; }
.yes-mark  { color: #1E7E34; font-weight: bold; font-size: 13px; }
.no-mark   { color: #C0392B; }
.total-row td { background: #F0F2F8; font-weight: bold; }
.total-val { font-size: 13px; font-weight: bold; color: #003087; text-align: center; }

/* Overall */
.overall-box { border: 2px solid #003087; padding: 12px 16px; text-align: center; margin-bottom: 12px; background: #FFF8E7; }
.overall-box .label { font-size: 11px; font-weight: bold; }
.overall-box .score { font-size: 36px; font-weight: bold; color: #CC5500; line-height: 1.1; }
.overall-box .sub { font-size: 10px; color: #888; }
.badge-excellent    { background: #C8E6C9; color: #2E7D32; padding: 2px 10px; border-radius: 10px; font-weight: bold; font-size: 11px; }
.badge-satisfactory { background: #FFF9C4; color: #F57F17; padding: 2px 10px; border-radius: 10px; font-weight: bold; font-size: 11px; }
.badge-needs        { background: #FFCDD2; color: #C62828; padding: 2px 10px; border-radius: 10px; font-weight: bold; font-size: 11px; }

/* Score row */
.score-grid { width: 100%; display: table; border: 1px solid #ccc; margin-bottom: 12px; }
.score-cell { display: table-cell; padding: 10px 14px; border-right: 1px solid #ccc; text-align: center; }
.score-cell:last-child { border-right: none; }
.score-cell .sc-label { font-size: 9px; color: #888; margin-bottom: 4px; }
.score-cell .sc-value { font-size: 22px; font-weight: bold; color: #003087; }
.score-cell .sc-max   { font-size: 9px; color: #aaa; }

/* Comments */
.comments-box { border: 1px solid #ccc; padding: 8px 10px; margin-bottom: 14px; min-height: 44px; font-size: 10px; }
.comments-box .lbl { font-weight: bold; font-size: 9px; color: #888; text-transform: uppercase; margin-bottom: 4px; }

/* Signature row */
.sig-table { width: 100%; border-collapse: collapse; margin-top: 16px; }
.sig-table td { width: 50%; padding: 0 8px; text-align: center; vertical-align: bottom; }
.sig-line { border-top: 1px solid #333; margin-top: 30px; padding-top: 4px; font-size: 10px; color: #555; font-style: italic; }
.sig-name { font-size: 11px; font-weight: bold; margin-bottom: 2px; }

/* Footer */
.doc-footer { text-align: right; font-size: 9px; color: #aaa; margin-top: 10px; border-top: 1px solid #ddd; padding-top: 4px; }
</style>
</head>
<body>
<div class="page">

  <div class="header">
    <h1>QUIRINO MEMORIAL MEDICAL CENTER</h1>
    <h2>GENERAL SERVICES SECTION</h2>
    <h3>JANITORIAL STAFF PERFORMANCE AND AREA QUALITY EVALUATION</h3>
  </div>

  <div class="info-grid">
    <div class="info-row">
      <div class="info-cell" style="width:35%">
        <strong>Janitor's Name</strong>{{ $evaluation->janitor->name }}
      </div>
      <div class="info-cell" style="width:25%">
        <strong>Area</strong>{{ $evaluation->area->name }}
      </div>
      <div class="info-cell" style="width:20%">
        <strong>Date</strong>{{ $evaluation->eval_date->format('F d, Y') }}
      </div>
      <div class="info-cell" style="width:20%">
        <strong>Time</strong>{{ $evaluation->eval_time }}
      </div>
    </div>
  </div>

  <div class="direction">
    <strong>Direction:</strong> This evaluation must be conducted by the Clinical Supervisor / Senior Nurse on a Daily basis.
    Head of the Housekeeping Unit or representative may also conduct a spot check on every area to validate the result of this evaluation.
  </div>

  @foreach($sections as $sectionKey => $criteria)
  @php
    $labels = ['A' => 'A. CLEANLINESS AND ORDERLINESS (50%)', 'B' => 'B. OVERALL PRACTICES AND BEHAVIOR (30%)', 'C' => 'C. COMPLIANCE WITH REGULATORY REQUIREMENT (20%)'];
    $maxPts = ['A' => 50, 'B' => 30, 'C' => 20];
    $sectionTotal = $evaluation->scores->where('section', $sectionKey)->sum('points_earned');
    $scoreMap = $evaluation->scores->keyBy('field_key');
  @endphp
  <div class="section-hdr">{{ $labels[$sectionKey] }}</div>
  <table>
    <thead>
      <tr>
        <th class="crit-col">Criteria</th>
        <th class="check-col">YES</th>
        <th class="check-col">NO</th>
        <th class="pts-col">Points</th>
        <th class="rem-col">Remarks</th>
      </tr>
    </thead>
    <tbody>
      @foreach($criteria as $item)
      @php $score = $scoreMap->get($item['field']); $compliant = $score && $score->is_compliant; @endphp
      <tr>
        <td>{{ $item['label'] }}</td>
        <td class="check-col"><span class="{{ $compliant ? 'yes-mark' : '' }}">{{ $compliant ? '✓' : '' }}</span></td>
        <td class="check-col"><span class="{{ !$compliant ? 'no-mark' : '' }}">{{ !$compliant ? '✓' : '' }}</span></td>
        <td class="pts-col" style="font-weight:bold; color:{{ $compliant ? '#1E7E34' : '#999' }}">{{ $score ? $score->points_earned : 0 }}</td>
        <td class="rem-col"></td>
      </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr class="total-row">
        <td colspan="3" style="text-align:right; font-size:10px">TOTAL POINTS (MAX. {{ $maxPts[$sectionKey] }})</td>
        <td class="total-val">{{ $sectionTotal }}</td>
        <td></td>
      </tr>
    </tfoot>
  </table>
  @endforeach

  <div class="overall-box">
    <div class="label">OVERALL RATING (A + B + C)</div>
    <div class="score">{{ $evaluation->total_score }}</div>
    <div class="sub">out of 100</div>
    <div style="margin-top:6px">
      @php $r = $evaluation->rating_label; @endphp
      <span class="{{ $r === 'Excellent' ? 'badge-excellent' : ($r === 'Satisfactory' ? 'badge-satisfactory' : 'badge-needs') }}">
        {{ $r }}
      </span>
    </div>
  </div>

  @if($evaluation->comments)
  <div class="comments-box">
    <div class="lbl">Comments / Suggestions for Improvement</div>
    {{ $evaluation->comments }}
  </div>
  @endif

  <table class="sig-table">
    <tr>
      <td>
        <div class="sig-name">{{ $evaluation->evaluator->name }}</div>
        <div class="sig-line">Evaluated by — Signature over printed name</div>
      </td>
      <td>
        <div class="sig-name">{{ $evaluation->noted_by ?? '______________________________' }}</div>
        <div class="sig-line">Noted by — Signature over printed name</div>
      </td>
    </tr>
  </table>

  <div class="doc-footer">GSU-005 | Rev 1 09 January 2026 | Generated: {{ now()->format('F d, Y h:i A') }}</div>
</div>
</body>
</html>