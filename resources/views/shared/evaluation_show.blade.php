@extends('layouts.app')
@section('page-title', 'Evaluation Detail')

@section('content')
<div class="page-header">
  <h1>Evaluation — {{ $evaluation->janitor->name }}</h1>
  <div style="display:flex; gap:8px">
    @if(auth()->user()->isAdmin())
      <a href="{{ route('admin.evaluations.pdf', $evaluation) }}" class="btn btn-secondary">⬇ Download PDF</a>
      <a href="{{ route('admin.evaluations.index') }}" class="btn btn-secondary">← Back</a>
    @elseif(auth()->user()->isEvaluator())
      <a href="{{ route('evaluator.history') }}" class="btn btn-secondary">← Back</a>
    @else
      <a href="{{ route('janitor.history') }}" class="btn btn-secondary">← Back</a>
    @endif
  </div>
</div>

{{-- Info strip ───────────────────────────────────────── --}}
<div class="card" style="margin-bottom:18px">
  <div class="card-body">
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(140px, 1fr)); gap:14px">
      <div><div style="font-size:11px; color:var(--muted); text-transform:uppercase; font-weight:600; margin-bottom:3px">Janitor</div><div style="font-weight:600">{{ $evaluation->janitor->name }}</div></div>
      <div><div style="font-size:11px; color:var(--muted); text-transform:uppercase; font-weight:600; margin-bottom:3px">Area</div><div>{{ $evaluation->area->name }}</div></div>
      <div><div style="font-size:11px; color:var(--muted); text-transform:uppercase; font-weight:600; margin-bottom:3px">Date</div><div>{{ $evaluation->eval_date->format('F d, Y') }}</div></div>
      <div><div style="font-size:11px; color:var(--muted); text-transform:uppercase; font-weight:600; margin-bottom:3px">Time</div><div>{{ $evaluation->eval_time }}</div></div>
      <div><div style="font-size:11px; color:var(--muted); text-transform:uppercase; font-weight:600; margin-bottom:3px">Evaluated By</div><div>{{ $evaluation->evaluator->name }}</div></div>
      @if($evaluation->noted_by)
      <div><div style="font-size:11px; color:var(--muted); text-transform:uppercase; font-weight:600; margin-bottom:3px">Noted By</div><div>{{ $evaluation->noted_by }}</div></div>
      @endif
    </div>
  </div>
</div>

{{-- Score cards ─────────────────────────────────────── --}}
<div style="display:grid; grid-template-columns:1fr 1fr 1fr 1.3fr; gap:14px; margin-bottom:18px">
  <div class="card" style="text-align:center; padding:18px">
    <div style="font-size:11px; color:var(--muted); text-transform:uppercase; font-weight:600; margin-bottom:6px">Section A<br><span style="text-transform:none; font-weight:400">Cleanliness</span></div>
    <div style="font-size:32px; font-weight:800; color:var(--navy)">{{ $evaluation->section_a_total }}</div>
    <div style="font-size:11px; color:var(--muted)">out of 50</div>
  </div>
  <div class="card" style="text-align:center; padding:18px">
    <div style="font-size:11px; color:var(--muted); text-transform:uppercase; font-weight:600; margin-bottom:6px">Section B<br><span style="text-transform:none; font-weight:400">Practices</span></div>
    <div style="font-size:32px; font-weight:800; color:var(--navy)">{{ $evaluation->section_b_total }}</div>
    <div style="font-size:11px; color:var(--muted)">out of 30</div>
  </div>
  <div class="card" style="text-align:center; padding:18px">
    <div style="font-size:11px; color:var(--muted); text-transform:uppercase; font-weight:600; margin-bottom:6px">Section C<br><span style="text-transform:none; font-weight:400">Compliance</span></div>
    <div style="font-size:32px; font-weight:800; color:var(--navy)">{{ $evaluation->section_c_total }}</div>
    <div style="font-size:11px; color:var(--muted)">out of 20</div>
  </div>
  <div class="card" style="text-align:center; padding:18px; border:2px solid var(--accent); background:#FFF8E7">
    <div style="font-size:11px; text-transform:uppercase; font-weight:600; color:var(--muted); margin-bottom:6px">Overall Rating</div>
    <div style="font-size:44px; font-weight:900; color:#CC5500; line-height:1">{{ $evaluation->total_score }}</div>
    <div style="font-size:11px; color:var(--muted); margin-bottom:8px">out of 100</div>
    @php $r = $evaluation->rating_label; @endphp
    <span class="badge {{ $r === 'Excellent' ? 'badge-excellent' : ($r === 'Satisfactory' ? 'badge-satisfactory' : 'badge-needs') }}" style="font-size:12px; padding:4px 14px">
      {{ $r }}
    </span>
  </div>
</div>

{{-- Criteria detail ─────────────────────────────────── --}}
@php
  $sectionMeta = [
    'A' => ['title' => 'A. Cleanliness and Orderliness (50%)',             'max' => 50],
    'B' => ['title' => 'B. Overall Practices and Behavior (30%)',          'max' => 30],
    'C' => ['title' => 'C. Compliance with Regulatory Requirement (20%)',  'max' => 20],
  ];
  $scoreMap = $evaluation->scores->keyBy('field_key');
@endphp

@foreach($sections as $key => $criteria)
<div class="card" style="margin-bottom:18px">
  <div class="card-header" style="background:var(--bg)">
    <h2>{{ $sectionMeta[$key]['title'] }}</h2>
    @php $sTotal = $evaluation->scores->where('section', $key)->sum('points_earned'); @endphp
    <span style="font-size:14px; font-weight:700; color:var(--navy-lt); margin-left:auto">
      {{ $sTotal }} / {{ $sectionMeta[$key]['max'] }}
    </span>
  </div>
  <div class="table-wrap">
    <table class="data-table">
      <thead>
        <tr>
          <th style="width:56%">Criteria</th>
          <th style="width:12%; text-align:center">Result</th>
          <th style="width:10%; text-align:center">Points</th>
          <th>—</th>
        </tr>
      </thead>
      <tbody>
        @foreach($criteria as $item)
        @php $score = $scoreMap->get($item['field']); $ok = $score && $score->is_compliant; @endphp
        <tr style="{{ $ok ? '' : 'opacity:.75' }}">
          <td style="font-size:12px">{{ $item['label'] }}</td>
          <td style="text-align:center">
            @if($ok)
              <span style="color:#1E7E34; font-weight:700; font-size:16px">✓</span>
              <span class="badge badge-active" style="margin-left:4px">YES</span>
            @else
              <span class="badge badge-inactive">NO</span>
            @endif
          </td>
          <td style="text-align:center; font-size:15px; font-weight:700; color:{{ $ok ? 'var(--success)' : '#bbb' }}">
            {{ $score ? $score->points_earned : 0 }}
          </td>
          <td style="color:var(--muted); font-size:12px">{{ $ok ? 'Compliant' : 'Non-compliant' }}</td>
        </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr style="background:#F8F9FC">
          <td colspan="2" style="text-align:right; font-weight:600; font-size:12px; color:var(--muted)">Section Total</td>
          <td style="text-align:center; font-size:16px; font-weight:700; color:var(--navy-lt)">{{ $sTotal }}</td>
          <td></td>
        </tr>
      </tfoot>
    </table>
  </div>
</div>
@endforeach

{{-- Comments ──────────────────────────────────────────── --}}
@if($evaluation->comments)
<div class="card">
  <div class="card-header"><h2>Comments / Suggestions</h2></div>
  <div class="card-body" style="font-size:13px; line-height:1.7">
    {{ $evaluation->comments }}
  </div>
</div>
@endif
@endsection