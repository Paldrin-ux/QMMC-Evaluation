@extends('layouts.app')
@section('page-title', isset($user) ? 'Edit Account' : 'Add Account')

@section('content')
<div class="page-header">
  <h1>{{ isset($user) ? 'Edit Account' : 'Add New Account' }}</h1>
  <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">← Back to Accounts</a>
</div>

<div class="card" style="max-width:700px">
  <div class="card-header"><h2>Account Details</h2></div>
  <div class="card-body">
    <form method="POST"
          action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}">
      @csrf
      @if(isset($user)) @method('PUT') @endif

      <div class="form-group">
        <label class="form-label" for="name">Full Name *</label>
        <input type="text" id="name" name="name"
               class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
               value="{{ old('name', $user->name ?? '') }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="form-group">
        <label class="form-label" for="email">Email Address *</label>
        <input type="email" id="email" name="email"
               class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
               value="{{ old('email', $user->email ?? '') }}" required>
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="form-row cols-2">
        <div class="form-group">
          <label class="form-label" for="password">
            Password {{ isset($user) ? '(leave blank to keep current)' : '*' }}
          </label>
          <input type="password" id="password" name="password"
                 class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                 {{ isset($user) ? '' : 'required' }} autocomplete="new-password">
          @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
          <label class="form-label" for="password_confirmation">Confirm Password</label>
          <input type="password" id="password_confirmation" name="password_confirmation"
                 class="form-control" autocomplete="new-password">
        </div>
      </div>

      <div class="form-row cols-2">
        <div class="form-group">
          <label class="form-label" for="role_id">Role *</label>
          <select id="role_id" name="role_id"
                  class="form-control {{ $errors->has('role_id') ? 'is-invalid' : '' }}" required>
            <option value="">Select role…</option>
            @foreach($roles as $role)
              <option value="{{ $role->id }}"
                {{ old('role_id', $user->role_id ?? '') == $role->id ? 'selected' : '' }}>
                {{ $role->name }}
              </option>
            @endforeach
          </select>
          @error('role_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
          <label class="form-label">Status</label>
          <div style="display:flex; gap:20px; padding-top:10px">
            <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-size:13px">
              <input type="radio" name="is_active" value="1"
                     {{ old('is_active', $user->is_active ?? true) ? 'checked' : '' }}>
              Active
            </label>
            <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-size:13px">
              <input type="radio" name="is_active" value="0"
                     {{ ! old('is_active', $user->is_active ?? true) ? 'checked' : '' }}>
              Inactive
            </label>
          </div>
        </div>
      </div>

      {{-- EVALUATOR: Assign Areas ──────────────────────── --}}
      <div id="area-assign-wrap" style="display:none">
        <div class="form-group">
          <label class="form-label">Assigned Areas
            <span style="font-weight:400;text-transform:none;letter-spacing:0;color:var(--muted)">
              — evaluator will see all janitors in these areas
            </span>
          </label>
          @error('area_ids')<div class="invalid-feedback" style="margin-bottom:6px">{{ $message }}</div>@enderror

          <input type="text" id="area-search" placeholder="Search areas…"
                 class="form-control" style="margin-bottom:8px" autocomplete="off"
                 oninput="filterAreas(this)">

          <div id="area-list" style="border:1px solid var(--border);border-radius:8px;max-height:260px;overflow-y:auto;background:var(--surface)">
            @foreach($areas as $area)
            <label class="area-row" data-name="{{ strtolower($area->name) }}"
                   style="display:flex;align-items:center;gap:10px;padding:8px 14px;border-bottom:1px solid var(--border);cursor:pointer;font-size:13px;transition:background .1s"
                   onmouseover="this.style.background='var(--bg)'"
                   onmouseout="if(!this.querySelector('input').checked)this.style.background=''">
              <input type="checkbox" name="area_ids[]" value="{{ $area->id }}"
                     style="width:15px;height:15px;flex-shrink:0;cursor:pointer;accent-color:var(--navy-lt)"
                     {{ in_array($area->id, old('area_ids', $assignedAreaIds ?? [])) ? 'checked' : '' }}
                     onchange="updateAreaStyles(this)">
              {{ $area->name }}
            </label>
            @endforeach
          </div>

          <div style="display:flex;justify-content:space-between;margin-top:6px;font-size:12px;color:var(--muted)">
            <span id="area-count">0 areas selected</span>
            <span>
              <a href="#" onclick="toggleAllAreas(true);return false" style="color:var(--navy-lt)">Select all</a>
              &nbsp;·&nbsp;
              <a href="#" onclick="toggleAllAreas(false);return false" style="color:var(--muted)">Clear all</a>
            </span>
          </div>
        </div>
      </div>

      {{-- JANITOR: Link profile ────────────────────────── --}}
      @if(!isset($user) && isset($janitors) && $janitors->isNotEmpty())
      <div id="janitor-link-wrap" style="display:none">
        <div class="form-group">
          <label class="form-label" for="janitor_id">Link to Janitor Profile</label>
          <select id="janitor_id" name="janitor_id" class="form-control">
            <option value="">— None —</option>
            @foreach($janitors as $j)
              <option value="{{ $j->id }}" {{ old('janitor_id') == $j->id ? 'selected' : '' }}>
                {{ $j->name }}
              </option>
            @endforeach
          </select>
          <div style="font-size:11px;color:var(--muted);margin-top:4px">
            Links this login to a janitor record so they can view their own evaluations.
          </div>
        </div>
      </div>
      @endif

      <div style="display:flex;gap:10px;margin-top:6px">
        <button type="submit" class="btn btn-primary">
          {{ isset($user) ? 'Save Changes' : 'Create Account' }}
        </button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
const roleSelect    = document.getElementById('role_id');
const areaWrap      = document.getElementById('area-assign-wrap');
const janitorWrap   = document.getElementById('janitor-link-wrap');

function syncVisibility() {
  const slug = roleSelect.options[roleSelect.selectedIndex]?.text?.toLowerCase() ?? '';
  if (areaWrap)   areaWrap.style.display   = slug.includes('evaluator') ? '' : 'none';
  if (janitorWrap) janitorWrap.style.display = slug.includes('janitor')   ? '' : 'none';
}

roleSelect.addEventListener('change', syncVisibility);
syncVisibility();

function filterAreas(input) {
  const q = input.value.toLowerCase();
  document.querySelectorAll('.area-row').forEach(row => {
    row.style.display = row.dataset.name.includes(q) ? '' : 'none';
  });
}

function updateAreaStyles(cb) {
  const label = cb.closest('label');
  label.style.background  = cb.checked ? '#EEF7EE' : '';
  label.style.borderColor = cb.checked ? '#A8D5A8' : '';
  updateAreaCount();
}

function updateAreaCount() {
  const n = document.querySelectorAll('input[name="area_ids[]"]:checked').length;
  document.getElementById('area-count').textContent = n + ' area' + (n !== 1 ? 's' : '') + ' selected';
}

function toggleAllAreas(state) {
  document.querySelectorAll('.area-row').forEach(row => {
    if (row.style.display !== 'none') {
      const cb = row.querySelector('input');
      cb.checked = state;
      updateAreaStyles(cb);
    }
  });
}

// Init checked styles on page load (for edit mode)
document.querySelectorAll('input[name="area_ids[]"]:checked').forEach(cb => {
  updateAreaStyles(cb);
});
updateAreaCount();
</script>
@endpush