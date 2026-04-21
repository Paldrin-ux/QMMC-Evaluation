@extends('layouts.app')
@section('page-title', 'Account Management')

@section('content')
<div class="page-header">
  <h1>Account Management</h1>
  <a href="{{ route('admin.users.create') }}" class="btn btn-primary">+ Add Account</a>
</div>

<form method="GET" action="{{ route('admin.users.index') }}">
  <div class="filter-bar">
    <div class="form-group">
      <label class="form-label">Search</label>
      <input type="text" name="search" class="form-control" placeholder="Name or email…"
             value="{{ request('search') }}">
    </div>
    <div class="form-group">
      <label class="form-label">Role</label>
      <select name="role" class="form-control">
        <option value="">All Roles</option>
        @foreach($roles as $role)
          <option value="{{ $role->slug }}" {{ request('role') === $role->slug ? 'selected' : '' }}>
            {{ $role->name }}
          </option>
        @endforeach
      </select>
    </div>
    <div class="form-group" style="align-self:flex-end">
      <button type="submit" class="btn btn-secondary">Filter</button>
      <a href="{{ route('admin.users.index') }}" class="btn btn-secondary" style="margin-left:6px">Clear</a>
    </div>
  </div>
</form>

<div class="card">
  <div class="card-header">
    <h2>Users</h2>
    <span style="font-size:12px; color:var(--muted); margin-left:auto">{{ $users->total() }} record(s)</span>
  </div>
  <div class="table-wrap">
    <table class="data-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Email</th>
          <th>Role</th>
          <th>Status</th>
          <th>Created</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($users as $user)
        <tr>
          <td style="color:var(--muted)">{{ $loop->iteration }}</td>
          <td>
            <div style="display:flex; align-items:center; gap:8px">
              <div style="width:28px; height:28px; border-radius:50%; background:var(--navy); display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:700; color:#fff; flex-shrink:0">
                {{ strtoupper(substr($user->name, 0, 2)) }}
              </div>
              <span style="font-weight:600">{{ $user->name }}</span>
              @if($user->id === auth()->id())
                <span style="font-size:10px; color:var(--muted)">(you)</span>
              @endif
            </div>
          </td>
          <td style="color:var(--muted)">{{ $user->email }}</td>
          <td>
            <span class="badge badge-{{ $user->role->slug }}">{{ $user->role->name }}</span>
          </td>
          <td>
            <span class="badge {{ $user->is_active ? 'badge-active' : 'badge-inactive' }}">
              {{ $user->is_active ? 'Active' : 'Inactive' }}
            </span>
          </td>
          <td style="color:var(--muted); font-size:12px">{{ $user->created_at->format('m/d/Y') }}</td>
          <td>
            <div class="td-actions">
              <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-secondary btn-sm">Edit</a>

              @if($user->id !== auth()->id())
              <form method="POST" action="{{ route('admin.users.toggle_status', $user) }}">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-sm {{ $user->is_active ? 'btn-danger' : 'btn-secondary' }}">
                  {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                </button>
              </form>
              @endif
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" style="text-align:center; padding:32px; color:var(--muted)">
            No users found.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($users->hasPages())
  <div class="card-footer" style="display:flex; justify-content:flex-end">
    {{ $users->links('vendor.pagination.simple') }}
  </div>
  @endif
</div>
@endsection