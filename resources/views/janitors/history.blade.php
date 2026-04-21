@extends('layouts.app')
@section('page-title', 'My Evaluations')

@section('content')
<div class="page-header">
  <h1>My Evaluation History</h1>
  <a href="{{ route('janitor.dashboard') }}" class="btn btn-secondary">← Dashboard</a>
</div>

<div class="card">
  <div class="card-header">
    <h2>All Evaluations</h2>
    <span style="font-size:12px; color:var(--muted); margin-left:auto">{{ $evaluations->total() }} record(s)</span>
  </div>
  <div class="table-wrap">
    <table class="data-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Date</th>
          <th>Area</th>
          <th style="text-align:center">Sec A /50</th>
          <th style="text-align:center">Sec B /30</th>
          <th style="text-align:center">Sec C /20</th>
          <th style="text-align:center">Total /100</th>
          <th>Rating</th>
          <th>Evaluated By</th>
          <th>Details</th>
        </tr>
      </thead>
      <tbody>
        @forelse($evaluations as $ev)
        <tr>
          <td style="color:var(--muted)">{{ $evaluations->firstItem() + $loop->index }}</td>
          <td>{{ $ev->eval_date->format('m/d/Y') }}</td>
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
          <td style="font-size:12px; color:var(--muted)">{{ $ev->evaluator->name }}</td>
          <td>
            <a href="{{ route('janitor.show', $ev) }}" class="btn btn-secondary btn-sm">View</a>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="10" style="text-align:center; padding:32px; color:var(--muted)">
            No evaluations on record yet.
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