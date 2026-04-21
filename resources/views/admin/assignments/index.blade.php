@extends('layouts.app')
@section('page-title', 'Evaluator Area Assignments')

@section('content')
<div class="page-header">
  <h1>Evaluator Area Assignments</h1>
</div>

<div style="font-size:13px;color:var(--muted);margin-bottom:18px;background:var(--bg);border:1px solid var(--border);border-radius:8px;padding:10px 14px">
  Assign <strong>areas</strong> to each evaluator. All janitors assigned to those areas will automatically appear in the evaluator's dashboard.
</div>

@foreach($evaluators as $evaluator)
@php $assignedIds = $evaluator->assignedAreas->pluck('id')->toArray(); @endphp

<div class="card" style="margin-bottom:16px">
  <div class="card-header" style="background:var(--bg)">
    <div style="display:flex;align-items:center;gap:10px;flex:1">
      <div style="width:32px;height:32px;border-radius:50%;background:var(--navy);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#fff">
        {{ strtoupper(substr($evaluator->name, 0, 2)) }}
      </div>
      <div>
        <div style="font-weight:600;font-size:14px">{{ $evaluator->name }}</div>
        <div style="font-size:11px;color:var(--muted)">{{ $evaluator->email }}</div>
      </div>
      <span class="badge badge-evaluator" style="margin-left:8px">Evaluator</span>
    </div>
    <span style="font-size:12px;color:var(--muted)">
      {{ count($assignedIds) }} area(s) assigned
    </span>
  </div>

  <div class="card-body">
    <form method="POST" action="{{ route('admin.assignments.update') }}">
      @csrf
      <input type="hidden" name="evaluator_id" value="{{ $evaluator->id }}">

      <input type="text" placeholder="Search areas…"
             oninput="filterItems(this,'block_{{ $evaluator->id }}')"
             class="form-control" style="margin-bottom:10px;max-width:300px">

      <div id="block_{{ $evaluator->id }}"
           style="display:grid;grid-template-columns:repeat(auto-fill,minmax(230px,1fr));gap:6px;max-height:240px;overflow-y:auto;padding:2px">
        @foreach($areas as $area)
        @php $checked = in_array($area->id, $assignedIds); @endphp
        <label class="item-row" data-name="{{ strtolower($area->name) }}"
               style="display:flex;align-items:center;gap:8px;padding:7px 10px;border:1px solid var(--border);border-radius:7px;cursor:pointer;font-size:13px;transition:background .1s;
               {{ $checked ? 'background:#EEF7EE;border-color:#A8D5A8;' : '' }}">
          <input type="checkbox" name="area_ids[]" value="{{ $area->id }}"
                 style="width:14px;height:14px;accent-color:var(--navy-lt);flex-shrink:0"
                 {{ $checked ? 'checked' : '' }}
                 onchange="toggleStyle(this)">
          <span style="font-size:12px">{{ $area->name }}</span>
        </label>
        @endforeach
      </div>

      <div style="display:flex;align-items:center;gap:10px;margin-top:12px;flex-wrap:wrap">
        <button type="submit" class="btn btn-primary btn-sm">Save Assignments</button>
        <button type="button" class="btn btn-secondary btn-sm"
                onclick="bulkToggle('block_{{ $evaluator->id }}',true)">Select All</button>
        <button type="button" class="btn btn-secondary btn-sm"
                onclick="bulkToggle('block_{{ $evaluator->id }}',false)">Clear All</button>
        @if($evaluator->assignedAreas->isNotEmpty())
        <span style="font-size:12px;color:var(--muted);margin-left:4px">
          Assigned: {{ $evaluator->assignedAreas->pluck('name')->join(', ') }}
        </span>
        @endif
      </div>
    </form>
  </div>
</div>
@endforeach

@if($evaluators->isEmpty())
<div class="card">
  <div class="card-body" style="text-align:center;padding:40px;color:var(--muted)">
    No evaluator accounts found.
    <a href="{{ route('admin.users.create') }}" style="color:var(--navy-lt)">Add an evaluator account first.</a>
  </div>
</div>
@endif
@endsection

@push('scripts')
<script>
function filterItems(input, blockId) {
  const q = input.value.toLowerCase();
  document.querySelectorAll('#' + blockId + ' .item-row').forEach(row => {
    row.style.display = row.dataset.name.includes(q) ? '' : 'none';
  });
}

function toggleStyle(cb) {
  const label = cb.closest('label');
  label.style.background  = cb.checked ? '#EEF7EE' : '';
  label.style.borderColor = cb.checked ? '#A8D5A8' : '';
}

function bulkToggle(blockId, state) {
  document.querySelectorAll('#' + blockId + ' input[type="checkbox"]').forEach(cb => {
    if (cb.closest('.item-row').style.display !== 'none') {
      cb.checked = state;
      toggleStyle(cb);
    }
  });
}
</script>
@endpush