@extends('layouts.app')
@section('page-title', 'My Dashboard')

@section('content')
<div class="page-header">
  <h1>Welcome, {{ $janitor->name }}</h1>
</div>

{{-- Summary stats ─────────────────────────────────────── --}}
<div class="stat-grid">
  <div class="stat-card accent">
    <div class="label">Total Evaluations</div>
    <div class="value">{{ $janitor->evaluations()->count() }}</div>
    <div class="sub">all time</div>
  </div>
  <div class="stat-card green">
    <div class="label">Average Score</div>
    <div class="value">{{ $average ? number_format($average, 1) : '—' }}</div>
    <div class="sub">out of 100</div>
  </div>
  <div class="stat-card">
    <div class="label">Latest Score</div>
    <div class="value">{{ $recent->first()?->total_score ?? '—' }}</div>
    <div class="sub">{{ $recent->first()?->eval_date?->format('M d, Y') ?? 'No evaluations yet' }}</div>
  </div>
  <div class="stat-card {{ ($recent->first()?->rating_label === 'Excellent') ? 'green' : (($recent->first()?->rating_label === 'Needs Improvement') ? 'red' : 'accent') }}">
    <div class="label">Latest Rating</div>
    <div class="value" style="font-size:18px; padding-top:4px">
      {{ $recent->first()?->rating_label ?? '—' }}
    </div>
  </div>
</div>

{{-- Recent evaluations ────────────────────────────────── --}}
<div class="card">
  <div class="card-header">
    <h2>Recent Evaluations</h2>
    <a href="{{ route('janitor.history') }}" class="btn btn-secondary btn-sm" style="margin-left:auto">
      View All
    </a>
  </div>
  <div class="table-wrap">
    <table class="data-table">
      <thead>
        <tr>
          <th>Date</th>
          <th>Area</th>
          <th>Sec A /50</th>
          <th>Sec B /30</th>
          <th>Sec C /20</th>
          <th>Total /100</th>
          <th>Rating</th>
          <th>Details</th>
        </tr>
      </thead>
      <tbody>
        @forelse($recent as $ev)
        <tr>
          <td>{{ $ev->eval_date->format('M d, Y') }}</td>
          <td style="font-size:12px">{{ $ev->area->name }}</td>
          <td style="text-align:center">{{ $ev->section_a_total }}</td>
          <td style="text-align:center">{{ $ev->section_b_total }}</td>
          <td style="text-align:center">{{ $ev->section_c_total }}</td>
          <td style="text-align:center; font-weight:700; font-size:16px">{{ $ev->total_score }}</td>
          <td>
            @php $r = $ev->rating_label; @endphp
            <span class="badge {{ $r === 'Excellent' ? 'badge-excellent' : ($r === 'Satisfactory' ? 'badge-satisfactory' : 'badge-needs') }}">
              {{ $r }}
            </span>
          </td>
          <td>
            <a href="{{ route('janitor.show', $ev) }}" class="btn btn-secondary btn-sm">View</a>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8" style="text-align:center; padding:32px; color:var(--muted)">
            No evaluations on record yet.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection