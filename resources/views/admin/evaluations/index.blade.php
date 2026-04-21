@extends('layouts.app')
@section('page-title', 'Evaluation Records')

@section('content')
<div class="page-header">
  <h1>Evaluation Records</h1>
  <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}"
     onclick="window.location='{{ route('admin.evaluations.export_list_pdf') }}?{{ http_build_query(request()->except('page')) }}'; return false;"
     class="btn btn-secondary">⬇ Export PDF</a>
</div>

{{-- Filters ──────────────────────────────────────────────── --}}
<form method="GET" action="{{ route('admin.evaluations.index') }}">
  <div class="filter-bar">
    <div class="form-group">
      <label class="form-label">Janitor</label>
      <select name="janitor_id" class="form-control">
        <option value="">All Janitors</option>
        @foreach($janitors as $j)
          <option value="{{ $j->id }}" {{ request('janitor_id') == $j->id ? 'selected' : '' }}>{{ $j->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="form-group">
      <label class="form-label">Area</label>
      <select name="area_id" class="form-control">
        <option value="">All Areas</option>
        @foreach($areas as $a)
          <option value="{{ $a->id }}" {{ request('area_id') == $a->id ? 'selected' : '' }}>{{ $a->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="form-group">
      <label class="form-label">Date From</label>
      <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
    </div>
    <div class="form-group">
      <label class="form-label">Date To</label>
      <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
    </div>
    <div class="form-group">
      <label class="form-label">Rating</label>
      <select name="rating" class="form-control">
        <option value="">All Ratings</option>
        <option value="Excellent"        {{ request('rating') === 'Excellent'        ? 'selected' : '' }}>Excellent</option>
        <option value="Satisfactory"     {{ request('rating') === 'Satisfactory'     ? 'selected' : '' }}>Satisfactory</option>
        <option value="Needs Improvement"{{ request('rating') === 'Needs Improvement'? 'selected' : '' }}>Needs Improvement</option>
      </select>
    </div>
    <div class="form-group" style="align-self:flex-end">
      <button type="submit" class="btn btn-secondary">Filter</button>
      <a href="{{ route('admin.evaluations.index') }}" class="btn btn-secondary" style="margin-left:6px">Clear</a>
    </div>
  </div>
</form>

<div class="card">
  <div class="card-header">
    <h2>Results</h2>
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
          <th>Sec A /50</th>
          <th>Sec B /30</th>
          <th>Sec C /20</th>
          <th>Total /100</th>
          <th>Rating</th>
          <th>Evaluated By</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($evaluations as $ev)
        <tr>
          <td style="color:var(--muted)">{{ $evaluations->firstItem() + $loop->index }}</td>
          <td style="font-weight:600">{{ $ev->janitor->name }}</td>
          <td style="font-size:12px">{{ $ev->area->name }}</td>
          <td>{{ $ev->eval_date->format('m/d/Y') }}</td>
          <td style="text-align:center">{{ $ev->section_a_total }}</td>
          <td style="text-align:center">{{ $ev->section_b_total }}</td>
          <td style="text-align:center">{{ $ev->section_c_total }}</td>
          <td style="text-align:center; font-weight:700; font-size:15px">{{ $ev->total_score }}</td>
          <td>
            @php $r = $ev->rating_label; @endphp
            <span class="badge {{ $r === 'Excellent' ? 'badge-excellent' : ($r === 'Satisfactory' ? 'badge-satisfactory' : 'badge-needs') }}">
              {{ $r }}
            </span>
          </td>
          <td style="font-size:12px; color:var(--muted)">{{ $ev->evaluator->name }}</td>
          <td>
            <div class="td-actions">
              <a href="{{ route('admin.evaluations.show', $ev) }}" class="btn btn-secondary btn-sm">View</a>
              <a href="{{ route('admin.evaluations.pdf', $ev) }}" class="btn btn-secondary btn-sm">PDF</a>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="11" style="text-align:center; padding:32px; color:var(--muted)">
            No evaluation records found for the selected filters.
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