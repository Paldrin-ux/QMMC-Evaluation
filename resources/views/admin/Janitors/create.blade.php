@extends('layouts.app')
@section('page-title', isset($janitor) ? 'Edit Janitor' : 'Add Janitor')

@section('content')
<div class="page-header">
  <h1>{{ isset($janitor) ? 'Edit Janitor' : 'Add New Janitor' }}</h1>
  <a href="{{ route('admin.janitors.index') }}" class="btn btn-secondary">← Back to List</a>
</div>

<div class="card" style="max-width:720px">
  <div class="card-header">
    <h2>Janitor Details</h2>
  </div>
  <div class="card-body">
    <form method="POST"
          action="{{ isset($janitor) ? route('admin.janitors.update', $janitor) : route('admin.janitors.store') }}">
      @csrf
      @if(isset($janitor)) @method('PUT') @endif

      <div class="form-row cols-2">
        <div class="form-group">
          <label class="form-label" for="name">Full Name *</label>
          <input type="text" id="name" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                 value="{{ old('name', $janitor->name ?? '') }}" required>
          @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
          <label class="form-label" for="employee_id">Employee ID</label>
          <input type="text" id="employee_id" name="employee_id"
                 class="form-control {{ $errors->has('employee_id') ? 'is-invalid' : '' }}"
                 value="{{ old('employee_id', $janitor->employee_id ?? ($nextEmployeeId ?? '')) }}" {{ isset($janitor) ? '' : 'readonly' }}>
          @error('employee_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Status</label>
        <div style="display:flex; gap:20px; padding-top:4px">
          <label style="display:flex; align-items:center; gap:6px; cursor:pointer; font-size:13px">
            <input type="radio" name="is_active" value="1"
                   {{ old('is_active', $janitor->is_active ?? true) ? 'checked' : '' }}>
            Active
          </label>
          <label style="display:flex; align-items:center; gap:6px; cursor:pointer; font-size:13px">
            <input type="radio" name="is_active" value="0"
                   {{ ! old('is_active', $janitor->is_active ?? true) ? 'checked' : '' }}>
            Inactive
          </label>
        </div>
      </div>

      {{-- Area multi-select ─────────────────────────────── --}}
      <div class="form-group">
        <label class="form-label">Assigned Areas * <span style="font-weight:400; text-transform:none; letter-spacing:0; color:var(--muted)">(select one or more)</span></label>
        @error('area_ids')<div class="invalid-feedback" style="margin-bottom:6px">{{ $message }}</div>@enderror

        <div id="area-search-wrap" style="position:relative; margin-bottom:8px">
          <input type="text" id="area-search" placeholder="Search areas…" class="form-control"
                 style="padding-left:34px" autocomplete="off">
          <span style="position:absolute; left:11px; top:50%; transform:translateY(-50%); color:var(--muted); font-size:14px">⌕</span>
        </div>

        <div id="area-list" style="
          border:1px solid var(--border); border-radius:8px; max-height:280px;
          overflow-y:auto; background:var(--surface);
        ">
          @foreach($areas as $area)
          <label style="
            display:flex; align-items:center; gap:10px;
            padding:9px 14px; border-bottom:1px solid var(--border);
            cursor:pointer; transition:background .1s; font-size:13px;
          " class="area-row" data-name="{{ strtolower($area->name) }}"
             onmouseover="this.style.background='#F4F6FA'"
             onmouseout="this.style.background=''">
            <input type="checkbox" name="area_ids[]" value="{{ $area->id }}"
                   style="width:15px; height:15px; flex-shrink:0; cursor:pointer; accent-color:var(--navy-lt)"
                   {{ in_array($area->id, old('area_ids', $assignedAreaIds ?? [])) ? 'checked' : '' }}>
            {{ $area->name }}
          </label>
          @endforeach
        </div>

        <div style="display:flex; justify-content:space-between; margin-top:6px; font-size:12px; color:var(--muted)">
          <span id="selected-count">0 areas selected</span>
          <a href="#" id="select-all" style="color:var(--navy-lt)">Select all</a>
          &nbsp;·&nbsp;
          <a href="#" id="clear-all" style="color:var(--muted)">Clear</a>
        </div>
      </div>

      <div style="display:flex; gap:10px; margin-top:6px">
        <button type="submit" class="btn btn-primary">
          {{ isset($janitor) ? 'Save Changes' : 'Create Janitor' }}
        </button>
        <a href="{{ route('admin.janitors.index') }}" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
const searchInput = document.getElementById('area-search');
const rows        = document.querySelectorAll('.area-row');
const countEl     = document.getElementById('selected-count');

function updateCount() {
  const n = document.querySelectorAll('input[name="area_ids[]"]:checked').length;
  countEl.textContent = n + ' area' + (n !== 1 ? 's' : '') + ' selected';
}

searchInput.addEventListener('input', () => {
  const q = searchInput.value.toLowerCase();
  rows.forEach(row => {
    row.style.display = row.dataset.name.includes(q) ? '' : 'none';
  });
});

document.getElementById('select-all').addEventListener('click', e => {
  e.preventDefault();
  rows.forEach(row => {
    if (row.style.display !== 'none') {
      row.querySelector('input').checked = true;
    }
  });
  updateCount();
});

document.getElementById('clear-all').addEventListener('click', e => {
  e.preventDefault();
  document.querySelectorAll('input[name="area_ids[]"]').forEach(cb => cb.checked = false);
  updateCount();
});

document.querySelectorAll('input[name="area_ids[]"]').forEach(cb => {
  cb.addEventListener('change', updateCount);
});

updateCount();
</script>
@endpush