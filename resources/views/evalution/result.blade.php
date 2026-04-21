<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Evaluation Result - QMMC</title>
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: Arial, sans-serif; font-size: 13px; background: #f0f0f0; padding: 20px; }
.page { background: white; max-width: 900px; margin: 0 auto; padding: 24px 32px; border: 1px solid #ccc; box-shadow: 0 2px 8px rgba(0,0,0,0.15); }
.header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
.header h1 { font-size: 15px; font-weight: bold; }
.header h2 { font-size: 13px; font-weight: normal; margin-top: 4px; }
.info-box { border: 1px solid #ccc; padding: 10px 14px; margin-bottom: 20px; background: #fafafa; }
.info-box p { margin: 3px 0; font-size: 13px; }
.result-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px; margin-bottom: 20px; }
.score-box { border: 2px solid #003087; padding: 14px; text-align: center; border-radius: 6px; }
.score-box .label { font-size: 11px; color: #555; margin-bottom: 6px; }
.score-box .value { font-size: 30px; font-weight: bold; color: #003087; }
.score-box .max { font-size: 11px; color: #999; margin-top: 4px; }
.overall-box { border: 3px solid #cc5500; padding: 20px; text-align: center; border-radius: 6px; background: #fff8e1; margin-bottom: 20px; }
.overall-box .label { font-size: 13px; font-weight: bold; margin-bottom: 6px; }
.overall-box .value { font-size: 48px; font-weight: bold; color: #cc5500; }
.overall-box .max { font-size: 13px; color: #999; }
.badge { display: inline-block; padding: 5px 18px; border-radius: 14px; font-weight: bold; font-size: 15px; margin-top: 8px; }
.excellent { background: #c8e6c9; color: #2e7d32; }
.satisfactory { background: #fff9c4; color: #f57f17; }
.needs-improvement { background: #ffcdd2; color: #c62828; }
.comments-box { border: 1px solid #ddd; padding: 10px; margin-bottom: 20px; background: #fafafa; }
.comments-box label { font-weight: bold; font-size: 12px; display: block; margin-bottom: 4px; }
.btn-row { display: flex; gap: 10px; margin-top: 10px; }
.btn { display: inline-block; padding: 9px 24px; background: #003087; color: white; text-decoration: none; border-radius: 4px; font-size: 13px; font-weight: bold; }
.btn-outline { background: white; color: #003087; border: 2px solid #003087; }
.btn:hover { opacity: 0.88; }
.doc-footer { text-align: right; font-size: 10px; color: #888; margin-top: 16px; border-top: 1px solid #ccc; padding-top: 4px; }
</style>
</head>
<body>
<div class="page">

  <div class="header">
    <h1>QUIRINO MEMORIAL MEDICAL CENTER — General Services Section</h1>
    <h2>Janitorial Staff Performance and Area Quality Evaluation — Result</h2>
  </div>

  <div class="info-box">
    <p><strong>Janitor Name:</strong> {{ $evaluation->janitor_name }}</p>
    <p><strong>Area:</strong> {{ $evaluation->area }}</p>
    <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($evaluation->date)->format('F d, Y') }} &nbsp;|&nbsp; <strong>Time:</strong> {{ $evaluation->time }}</p>
    @if($evaluation->evaluated_by)
    <p><strong>Evaluated by:</strong> {{ $evaluation->evaluated_by }}</p>
    @endif
    @if($evaluation->noted_by)
    <p><strong>Noted by:</strong> {{ $evaluation->noted_by }}</p>
    @endif
  </div>

  <div class="result-grid">
    <div class="score-box">
      <div class="label">Section A<br>Cleanliness &amp; Orderliness</div>
      <div class="value">{{ $evaluation->section_a_total }}</div>
      <div class="max">out of 50</div>
    </div>
    <div class="score-box">
      <div class="label">Section B<br>Practices &amp; Behavior</div>
      <div class="value">{{ $evaluation->section_b_total }}</div>
      <div class="max">out of 30</div>
    </div>
    <div class="score-box">
      <div class="label">Section C<br>Regulatory Compliance</div>
      <div class="value">{{ $evaluation->section_c_total }}</div>
      <div class="max">out of 20</div>
    </div>
  </div>

  <div class="overall-box">
    <div class="label">OVERALL RATING (A + B + C)</div>
    <div class="value">{{ $evaluation->overall_rating }}</div>
    <div class="max">out of 100</div>
    @php $score = $evaluation->overall_rating; @endphp
    @if($score >= 90)
      <span class="badge excellent">Excellent</span>
    @elseif($score >= 70)
      <span class="badge satisfactory">Satisfactory</span>
    @else
      <span class="badge needs-improvement">Needs Improvement</span>
    @endif
  </div>

  @if($evaluation->comments)
  <div class="comments-box">
    <label>Comments / Suggestions for Improvement:</label>
    <p>{{ $evaluation->comments }}</p>
  </div>
  @endif

  <div class="btn-row">
    <a href="{{ route('evaluation.create') }}" class="btn">+ New Evaluation</a>
    <a href="{{ route('evaluation.list') }}" class="btn btn-outline">View All Records</a>
  </div>

  <div class="doc-footer">GSU - 005 &nbsp;|&nbsp; Rev 1 09 January 2026</div>
</div>
</body>
</html>
