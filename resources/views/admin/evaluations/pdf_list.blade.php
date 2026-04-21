<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 10px; color: #1A2340; }
.page { padding: 20px 28px; }
.header { text-align: center; border-bottom: 2px solid #002060; padding-bottom: 8px; margin-bottom: 12px; }
.header h1 { font-size: 12px; font-weight: bold; }
.header h2 { font-size: 10px; font-weight: normal; margin-top: 2px; }
.meta { display: flex; justify-content: space-between; font-size: 9px; color: #888; margin-bottom: 10px; }
table { width: 100%; border-collapse: collapse; font-size: 9px; }
th { background: #002060; color: #fff; padding: 6px 8px; text-align: left; font-weight: bold; }
td { border-bottom: 1px solid #e0e4ee; padding: 6px 8px; vertical-align: middle; }
tr:nth-child(even) td { background: #F8F9FC; }
.score { font-weight: bold; font-size: 11px; text-align: center; }
.badge { display: inline-block; padding: 1px 7px; border-radius: 8px; font-size: 8px; font-weight: bold; }
.excellent    { background: #C8E6C9; color: #2E7D32; }
.satisfactory { background: #FFF9C4; color: #F57F17; }
.needs        { background: #FFCDD2; color: #C62828; }
.footer { text-align: right; font-size: 8px; color: #aaa; margin-top: 12px; border-top: 1px solid #ddd; padding-top: 4px; }
</style>
</head>
<body>
<div class="page">
  <div class="header">
    <h1>QUIRINO MEMORIAL MEDICAL CENTER — General Services Section</h1>
    <h2>Janitorial Staff Performance Evaluation Records</h2>
  </div>
  <div class="meta">
    <span>Generated: {{ now()->format('F d, Y h:i A') }}</span>
    <span>Total records: {{ $evaluations->count() }}</span>
  </div>
  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Janitor Name</th>
        <th>Area</th>
        <th>Date</th>
        <th>Time</th>
        <th style="text-align:center">Sec A /50</th>
        <th style="text-align:center">Sec B /30</th>
        <th style="text-align:center">Sec C /20</th>
        <th style="text-align:center">Total /100</th>
        <th>Rating</th>
        <th>Evaluated By</th>
      </tr>
    </thead>
    <tbody>
      @foreach($evaluations as $i => $ev)
      <tr>
        <td>{{ $i + 1 }}</td>
        <td style="font-weight:600">{{ $ev->janitor->name }}</td>
        <td>{{ $ev->area->name }}</td>
        <td>{{ $ev->eval_date->format('m/d/Y') }}</td>
        <td>{{ $ev->eval_time }}</td>
        <td class="score">{{ $ev->section_a_total }}</td>
        <td class="score">{{ $ev->section_b_total }}</td>
        <td class="score">{{ $ev->section_c_total }}</td>
        <td class="score" style="font-size:13px">{{ $ev->total_score }}</td>
        <td>
          @php $r = $ev->rating_label; @endphp
          <span class="badge {{ $r === 'Excellent' ? 'excellent' : ($r === 'Satisfactory' ? 'satisfactory' : 'needs') }}">
            {{ $r }}
          </span>
        </td>
        <td>{{ $ev->evaluator->name }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
  <div class="footer">GSU-005 | Rev 1 09 January 2026</div>
</div>
</body>
</html>