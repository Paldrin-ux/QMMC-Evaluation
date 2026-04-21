@extends('layouts.app')
@section('page-title', 'My Submissions')

@section('content')
<div class="page-header">
  <h1>My Submission History</h1>
  <a href="{{ route('evaluator.dashboard') }}" class="btn btn-secondary">← Assigned Janitors</a>
</div>

<form method="GET" action="{{ route('evaluator.history') }}">
  <div class="filter-bar">
    <div class="form-group">
      <label class="form-label">Search Janitor</label>
      <input type="text" name="search" class="form-control" placeholder="Janitor name…"
             value="{{ request('search') }}">
    </div>
    <div class="form-group" style="align-self:flex-end">
      <button type="submit" class="btn btn-secondary">Search</button>
      <a href="{{ route('evaluator.history') }}" class="btn btn-secondary" style="margin-left:6px">Clear</a>
    </div>
  </div>
</form>

<div class="card">
  <div class="card-header">
    <h2>Evaluations</h2>
    <span style="font-size:12px; color:var(--muted); margin-left:auto">{{ $evaluations->total() }} record(s)</span>
  </div>
  <div class="table-wrap">
    <table class="data-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Janitor</th>
          <th>Area</th>
          <th>Date</th>
          <th style="text-align:center">Score /100</th>
          <th>Rating</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($evaluations as $ev)
        <tr>
          <td style="color:var(--muted)">{{ $evaluations->firstItem() + $loop->index }}</td>
          <td style="font-weight:600">{{ $ev->janitor->name }}</td>
          <td style="font-size:12px; color:var(--muted)">{{ $ev->area->name }}</td>
          <td>{{ $ev->eval_date->format('m/d/Y') }}</td>
          <td style="text-align:center; font-size:16px; font-weight:700">{{ $ev->total_score }}</td>
          <td>
            @php $r = $ev->rating_label; @endphp
            <span class="badge {{ $r === 'Excellent' ? 'badge-excellent' : ($r === 'Satisfactory' ? 'badge-satisfactory' : 'badge-needs') }}">
              {{ $r }}
            </span>
          </td>
          <td>
            <a href="{{ route('evaluator.evaluate.show', $ev) }}" class="btn btn-secondary btn-sm">View</a>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" style="text-align:center; padding:32px; color:var(--muted)">
            No submissions yet.
            <a href="{{ route('evaluator.dashboard') }}" style="color:var(--navy-lt)">Go evaluate a janitor.</a>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($evaluations->hasPages())
  <div class="card-footer" style="display:flex; justify-content:flex-end">
    {{ $evaluations->links('vendor.pagination.simple') }}
  </div>
  @endif
</div>
@endsection