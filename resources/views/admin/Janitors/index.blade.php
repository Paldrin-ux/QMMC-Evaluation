@extends('layouts.app')
@section('page-title', 'Janitor Management')

@section('content')
<div class="page-header">
  <h1>Janitor Management</h1>
  <a href="{{ route('admin.janitors.create') }}" class="btn btn-primary">
    + Add Janitor
  </a>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.janitors.index') }}">
  <div class="filter-bar">
    <div class="form-group">
      <label class="form-label">Search</label>
      <input type="text" name="search" class="form-control" placeholder="Name or Employee ID…"
             value="{{ request('search') }}">
    </div>
    <div class="form-group">
      <label class="form-label">Status</label>
      <select name="status" class="form-control">
        <option value="">All</option>
        <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Active</option>
        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
      </select>
    </div>
    <div class="form-group" style="align-self:flex-end">
      <button type="submit" class="btn btn-secondary">Filter</button>
      <a href="{{ route('admin.janitors.index') }}" class="btn btn-secondary" style="margin-left:6px">Clear</a>
    </div>
  </div>
</form>

<div class="card">
  <div class="card-header">
    <h2>Janitors</h2>
    <span style="font-size:12px; color:var(--muted); margin-left:auto">
      {{ $janitors->total() }} record(s)
    </span>
  </div>
  <div class="table-wrap">
    <table class="data-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Employee ID</th>
          <th>Assigned Areas</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($janitors as $janitor)
        <tr>
          <td style="color:var(--muted)">{{ $loop->iteration }}</td>
          <td style="font-weight:600">{{ $janitor->name }}</td>
          <td>{{ $janitor->employee_id ?? '—' }}</td>
          <td>
            @if($janitor->areas->isEmpty())
              <span style="color:var(--muted)">No areas assigned</span>
            @else
              @foreach($janitor->areas->take(3) as $area)
                <span class="badge" style="background:#EEF2FF; color:#3730A3; margin:1px;">{{ $area->name }}</span>
              @endforeach
              @if($janitor->areas->count() > 3)
                <span style="font-size:11px; color:var(--muted)">+{{ $janitor->areas->count() - 3 }} more</span>
              @endif
            @endif
          </td>
          <td>
            <span class="badge {{ $janitor->is_active ? 'badge-active' : 'badge-inactive' }}">
              {{ $janitor->is_active ? 'Active' : 'Inactive' }}
            </span>
          </td>
          <td>
            <div class="td-actions">
              <a href="{{ route('admin.janitors.edit', $janitor) }}" class="btn btn-secondary btn-sm">Edit</a>
              <form method="POST" action="{{ route('admin.janitors.destroy', $janitor) }}"
                    onsubmit="return confirm('Delete {{ $janitor->name }}? This cannot be undone.')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" style="text-align:center; padding:32px; color:var(--muted)">
            No janitors found. <a href="{{ route('admin.janitors.create') }}" style="color:var(--navy-lt)">Add the first one.</a>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($janitors->hasPages())
  <div class="card-footer" style="display:flex; justify-content:flex-end">
    {{ $janitors->links('vendor.pagination.simple') }}
  </div>
  @endif
</div>
@endsection