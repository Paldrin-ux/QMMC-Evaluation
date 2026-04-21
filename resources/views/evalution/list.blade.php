<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Evaluation Records - QMMC</title>
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: Arial, sans-serif; font-size: 13px; background: #f0f0f0; padding: 20px; }
.page { background: white; max-width: 1000px; margin: 0 auto; padding: 24px 32px; border: 1px solid #ccc; box-shadow: 0 2px 8px rgba(0,0,0,0.15); }
.header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 16px; }
.header h1 { font-size: 15px; font-weight: bold; }
.header h2 { font-size: 12px; font-weight: normal; margin-top: 3px; }
.btn { display: inline-block; padding: 8px 22px; background: #003087; color: white; text-decoration: none; border-radius: 4px; font-size: 13px; font-weight: bold; margin-bottom: 14px; }
.btn:hover { opacity: 0.88; }
table { width: 100%; border-collapse: collapse; }
th, td { border: 1px solid #ccc; padding: 7px 10px; text-align: left; }
th { background: #e0e0e0; font-weight: bold; text-align: center; }
td { text-align: center; }
td:nth-child(2) { text-align: left; }
td:nth-child(3) { text-align: left; }
tr:hover { background: #f9f9f9; }
.excellent { color: #2e7d32; font-weight: bold; }
.satisfactory { color: #f57f17; font-weight: bold; }
.needs-improvement { color: #c62828; font-weight: bold; }
.empty-row td { text-align: center; color: #999; padding: 20px; }
.doc-footer { text-align: right; font-size: 10px; color: #888; margin-top: 16px; border-top: 1px solid #ccc; padding-top: 4px; }
</style>
</head>
<body>
<div class="page">
  <div class="header">
    <h1>QUIRINO MEMORIAL MEDICAL CENTER — General Services Section</h1>
    <h2>Janitorial Staff Performance Evaluation Records</h2>
  </div>

  <a href="{{ route('evaluation.create') }}" class="btn">+ New Evaluation</a>

  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Janitor Name</th>
        <th>Area</th>
        <th>Date</th>
        <th>Sec A (/50)</th>
        <th>Sec B (/30)</th>
        <th>Sec C (/20)</th>
        <th>Overall (/100)</th>
        <th>Rating</th>
      </tr>
    </thead>
    <tbody>
      @forelse($evaluations as $i => $ev)
      <tr>
        <td>{{ $i + 1 }}</td>
        <td>{{ $ev->janitor_name }}</td>
        <td>{{ $ev->area }}</td>
        <td>{{ \Carbon\Carbon::parse($ev->date)->format('m/d/Y') }}</td>
        <td>{{ $ev->section_a_total }}</td>
        <td>{{ $ev->section_b_total }}</td>
        <td>{{ $ev->section_c_total }}</td>
        <td><strong>{{ $ev->overall_rating }}</strong></td>
        <td>
          @php $s = $ev->overall_rating; @endphp
          @if($s >= 90)
            <span class="excellent">Excellent</span>
          @elseif($s >= 70)
            <span class="satisfactory">Satisfactory</span>
          @else
            <span class="needs-improvement">Needs Improvement</span>
          @endif
        </td>
      </tr>
      @empty
      <tr class="empty-row">
        <td colspan="9">No evaluations found. Click "+ New Evaluation" to get started.</td>
      </tr>
      @endforelse
    </tbody>
  </table>

  <div class="doc-footer">GSU - 005 &nbsp;|&nbsp; Rev 1 09 January 2026</div>
</div>
</body>
</html>
