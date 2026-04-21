@extends('layouts.app')
@section('page-title', 'Admin Dashboard')

@section('content')
<div class="page-header">
  <h1>Overview</h1>
</div>

<div class="stat-grid">
  <div class="stat-card accent">
    <div class="label">Total Janitors</div>
    <div class="value">{{ \App\Models\Janitor::count() }}</div>
    <div class="sub">{{ \App\Models\Janitor::where('is_active', true)->count() }} active</div>
  </div>
  <div class="stat-card green">
    <div class="label">Evaluations This Month</div>
    <div class="value">{{ \App\Models\Evaluation::whereMonth('eval_date', now()->month)->whereYear('eval_date', now()->year)->count() }}</div>
    <div class="sub">{{ now()->format('F Y') }}</div>
  </div>
  <div class="stat-card">
    <div class="label">Total Evaluations</div>
    <div class="value">{{ \App\Models\Evaluation::count() }}</div>
    <div class="sub">all time</div>
  </div>
  <div class="stat-card">
    <div class="label">Evaluator Accounts</div>
    <div class="value">{{ \App\Models\User::whereHas('role', fn($q) => $q->where('slug', 'evaluator'))->count() }}</div>
    <div class="sub">active staff</div>
  </div>
</div>

@php
  $recentEvals = \App\Models\Evaluation::with(['janitor', 'area', 'evaluator'])
    ->latest('eval_date')->take(8)->get();
  $excellent    = \App\Models\Evaluation::where('rating_label', 'Excellent')->count();
  $satisfactory = \App\Models\Evaluation::where('rating_label', 'Satisfactory')->count();
  $needs        = \App\Models\Evaluation::where('rating_label', 'Needs Improvement')->count();
  $total        = max($excellent + $satisfactory + $needs, 1);
@endphp

<div style="display:grid; grid-template-columns:2fr 1fr; gap:18px; align-items:start">

  {{-- Recent evaluations ──────────────────────────────── --}}
  <div class="card">
    <div class="card-header">
      <h2>Recent Evaluations</h2>
      <a href="{{ route('admin.evaluations.index') }}" class="btn btn-secondary btn-sm" style="margin-left:auto">View All</a>
    </div>
    <div class="table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>Janitor</th>
            <th>Area</th>
            <th>Date</th>
            <th>Score</th>
            <th>Rating</th>
          </tr>
        </thead>
        <tbody>
          @forelse($recentEvals as $ev)
          <tr>
            <td style="font-weight:600">{{ $ev->janitor->name }}</td>
            <td style="font-size:12px; color:var(--muted)">{{ Str::limit($ev->area->name, 28) }}</td>
            <td>{{ $ev->eval_date->format('m/d/Y') }}</td>
            <td style="font-weight:700; font-size:15px; text-align:center">{{ $ev->total_score }}</td>
            <td>
              @php $r = $ev->rating_label; @endphp
              <span class="badge {{ $r === 'Excellent' ? 'badge-excellent' : ($r === 'Satisfactory' ? 'badge-satisfactory' : 'badge-needs') }}">
                {{ $r }}
              </span>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" style="text-align:center; padding:28px; color:var(--muted)">No evaluations yet.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- Rating distribution ─────────────────────────────── --}}
  <div class="card">
    <div class="card-header"><h2>Rating Distribution</h2></div>
    <div class="card-body">
      @foreach([
        ['label' => 'Excellent',         'count' => $excellent,    'cls' => 'badge-excellent',    'color' => '#1E7E34'],
        ['label' => 'Satisfactory',      'count' => $satisfactory, 'cls' => 'badge-satisfactory', 'color' => '#856404'],
        ['label' => 'Needs Improvement', 'count' => $needs,        'cls' => 'badge-needs',        'color' => '#842029'],
      ] as $item)
      <div style="margin-bottom:14px">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:5px">
          <span style="font-size:13px; font-weight:600; color:{{ $item['color'] }}">{{ $item['label'] }}</span>
          <span style="font-size:13px; font-weight:700">{{ $item['count'] }}</span>
        </div>
        <div style="background:var(--border); border-radius:4px; height:8px; overflow:hidden">
          <div style="background:{{ $item['color'] }}; height:100%; width:{{ round($item['count'] / $total * 100) }}%; border-radius:4px; transition:width .4s"></div>
        </div>
        <div style="font-size:11px; color:var(--muted); margin-top:3px">{{ round($item['count'] / $total * 100) }}% of all evaluations</div>
      </div>
      @endforeach

      <div style="border-top:1px solid var(--border); padding-top:14px; margin-top:6px">
        <a href="{{ route('admin.janitors.create') }}" class="btn btn-primary" style="width:100%; justify-content:center; margin-bottom:8px">+ Add Janitor</a>
        <a href="{{ route('admin.users.create') }}"    class="btn btn-secondary" style="width:100%; justify-content:center">+ Add Account</a>
      </div>
    </div>
  </div>

</div>
@endsection