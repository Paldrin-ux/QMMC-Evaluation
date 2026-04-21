<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'QMMC GSS') — QMMC General Services</title>
<style>
/* ─── Reset & base ─────────────────────────────────────── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
:root {
  --navy:    #002060;
  --navy-lt: #003087;
  --accent:  #E8A020;
  --bg:      #F4F6FA;
  --surface: #FFFFFF;
  --border:  #E0E4EE;
  --text:    #1A2340;
  --muted:   #6B7494;
  --success: #1E7E34;
  --danger:  #C0392B;
  --warning: #B7791F;
  --sidebar-w: 240px;
  --topbar-h:  56px;
}
html, body { height: 100%; font-family: 'Segoe UI', system-ui, sans-serif; font-size: 14px; color: var(--text); background: var(--bg); }
a { color: inherit; text-decoration: none; }
input, select, textarea, button { font-family: inherit; font-size: 14px; }

/* ─── Layout shell ─────────────────────────────────────── */
.shell { display: flex; height: 100vh; overflow: hidden; }

/* ─── Sidebar ──────────────────────────────────────────── */
.sidebar {
  width: var(--sidebar-w);
  background: var(--navy);
  display: flex; flex-direction: column;
  flex-shrink: 0;
  overflow-y: auto;
}
.sidebar-logo {
  padding: 18px 20px 14px;
  border-bottom: 1px solid rgba(255,255,255,0.08);
  display: flex; align-items: center; gap: 10px;
}
.sidebar-logo .logo-badge {
  width: 36px; height: 36px; border-radius: 8px;
  background: var(--accent); display: flex; align-items: center;
  justify-content: center; font-weight: 700; font-size: 13px; color: #fff; flex-shrink: 0;
}
.sidebar-logo .logo-text { color: #fff; font-size: 13px; font-weight: 600; line-height: 1.3; }
.sidebar-logo .logo-text span { display: block; font-weight: 400; font-size: 11px; opacity: .65; margin-top: 1px; }

.sidebar-section { padding: 18px 12px 6px; }
.sidebar-section-label { font-size: 10px; font-weight: 700; letter-spacing: .08em; color: rgba(255,255,255,0.35); text-transform: uppercase; padding: 0 8px 6px; }
.sidebar-nav a {
  display: flex; align-items: center; gap: 10px;
  padding: 9px 10px; border-radius: 7px;
  color: rgba(255,255,255,0.72); font-size: 13px; font-weight: 500;
  transition: background .15s, color .15s; margin-bottom: 1px;
}
.sidebar-nav a:hover { background: rgba(255,255,255,0.08); color: #fff; }
.sidebar-nav a.active { background: rgba(255,255,255,0.14); color: #fff; }
.sidebar-nav a .icon { width: 16px; height: 16px; opacity: .7; flex-shrink: 0; }
.sidebar-nav a.active .icon { opacity: 1; }

.sidebar-footer {
  margin-top: auto; padding: 12px;
  border-top: 1px solid rgba(255,255,255,0.08);
}
.user-card {
  display: flex; align-items: center; gap: 10px;
  padding: 8px 10px; border-radius: 7px;
  background: rgba(255,255,255,0.07);
}
.user-card .avatar {
  width: 30px; height: 30px; border-radius: 50%;
  background: var(--accent); display: flex; align-items: center;
  justify-content: center; font-size: 12px; font-weight: 700; color: #fff; flex-shrink: 0;
}
.user-card .info { flex: 1; min-width: 0; }
.user-card .info .uname { color: #fff; font-size: 12px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.user-card .info .urole { color: rgba(255,255,255,.45); font-size: 11px; }
.user-card form button {
  background: none; border: none; cursor: pointer;
  color: rgba(255,255,255,.4); font-size: 18px; line-height: 1; padding: 0 2px;
  transition: color .15s;
}
.user-card form button:hover { color: rgba(255,255,255,.8); }

/* ─── Main area ─────────────────────────────────────────── */
.main { flex: 1; display: flex; flex-direction: column; overflow: hidden; }

.topbar {
  height: var(--topbar-h); background: var(--surface);
  border-bottom: 1px solid var(--border);
  display: flex; align-items: center; padding: 0 24px; gap: 12px;
  flex-shrink: 0;
}
.topbar-title { font-size: 16px; font-weight: 600; color: var(--text); }
.topbar-breadcrumb { font-size: 12px; color: var(--muted); display: flex; align-items: center; gap: 6px; }
.topbar-breadcrumb a { color: var(--navy-lt); }
.topbar-breadcrumb a:hover { text-decoration: underline; }
.topbar-spacer { flex: 1; }

.content { flex: 1; overflow-y: auto; padding: 24px; }

/* ─── Cards ─────────────────────────────────────────────── */
.card { background: var(--surface); border: 1px solid var(--border); border-radius: 10px; }
.card-header { padding: 16px 20px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 12px; }
.card-header h2 { font-size: 15px; font-weight: 600; }
.card-body { padding: 20px; }
.card-footer { padding: 14px 20px; border-top: 1px solid var(--border); background: #FAFBFD; border-radius: 0 0 10px 10px; }

/* ─── Stat cards ────────────────────────────────────────── */
.stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 14px; margin-bottom: 24px; }
.stat-card { background: var(--surface); border: 1px solid var(--border); border-radius: 10px; padding: 18px 20px; }
.stat-card .label { font-size: 12px; color: var(--muted); margin-bottom: 6px; }
.stat-card .value { font-size: 28px; font-weight: 700; color: var(--navy); }
.stat-card .sub   { font-size: 11px; color: var(--muted); margin-top: 3px; }
.stat-card.accent { border-left: 3px solid var(--accent); }
.stat-card.green  { border-left: 3px solid var(--success); }
.stat-card.red    { border-left: 3px solid var(--danger); }

/* ─── Buttons ───────────────────────────────────────────── */
.btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 7px; font-size: 13px; font-weight: 600; cursor: pointer; border: 1px solid transparent; transition: opacity .15s, background .15s; }
.btn-primary   { background: var(--navy-lt); color: #fff; }
.btn-primary:hover { background: var(--navy); }
.btn-secondary { background: var(--surface); color: var(--text); border-color: var(--border); }
.btn-secondary:hover { background: var(--bg); }
.btn-danger    { background: var(--danger); color: #fff; }
.btn-danger:hover { opacity: .88; }
.btn-sm { padding: 5px 10px; font-size: 12px; }

/* ─── Tables ────────────────────────────────────────────── */
.table-wrap { overflow-x: auto; }
table.data-table { width: 100%; border-collapse: collapse; font-size: 13px; }
table.data-table th { background: #F8F9FC; border-bottom: 2px solid var(--border); padding: 10px 14px; text-align: left; font-weight: 600; font-size: 12px; color: var(--muted); white-space: nowrap; }
table.data-table td { border-bottom: 1px solid var(--border); padding: 11px 14px; vertical-align: middle; }
table.data-table tbody tr:last-child td { border-bottom: none; }
table.data-table tbody tr:hover td { background: #F8F9FC; }
.td-actions { display: flex; align-items: center; gap: 6px; white-space: nowrap; }

/* ─── Badges ────────────────────────────────────────────── */
.badge { display: inline-flex; align-items: center; padding: 3px 9px; border-radius: 20px; font-size: 11px; font-weight: 600; }
.badge-excellent    { background: #D4EDDA; color: #1E5928; }
.badge-satisfactory { background: #FFF3CD; color: #856404; }
.badge-needs        { background: #F8D7DA; color: #842029; }
.badge-active   { background: #D4EDDA; color: #1E5928; }
.badge-inactive { background: #F8D7DA; color: #842029; }
.badge-admin     { background: #CCE5FF; color: #004085; }
.badge-evaluator { background: #D4EDDA; color: #155724; }
.badge-janitor   { background: #E2D9F3; color: #3D2A6B; }

/* ─── Forms ─────────────────────────────────────────────── */
.form-group { margin-bottom: 16px; }
.form-label { display: block; font-size: 12px; font-weight: 600; color: var(--muted); margin-bottom: 5px; text-transform: uppercase; letter-spacing: .04em; }
.form-control { width: 100%; padding: 9px 12px; border: 1px solid var(--border); border-radius: 7px; outline: none; background: var(--surface); transition: border-color .15s; }
.form-control:focus { border-color: var(--navy-lt); box-shadow: 0 0 0 3px rgba(0,48,135,0.1); }
.form-control.is-invalid { border-color: var(--danger); }
.invalid-feedback { color: var(--danger); font-size: 11px; margin-top: 4px; }
.form-row { display: grid; gap: 16px; }
.form-row.cols-2 { grid-template-columns: 1fr 1fr; }
.form-row.cols-3 { grid-template-columns: 1fr 1fr 1fr; }

/* ─── Alerts ────────────────────────────────────────────── */
.alert { padding: 12px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 16px; display: flex; align-items: flex-start; gap: 10px; }
.alert-success { background: #D4EDDA; color: #155724; border: 1px solid #C3E6CB; }
.alert-danger  { background: #F8D7DA; color: #721C24; border: 1px solid #F5C6CB; }

/* ─── Filters bar ──────────────────────────────────────── */
.filter-bar { display: flex; flex-wrap: wrap; gap: 10px; align-items: flex-end; margin-bottom: 18px; }
.filter-bar .form-group { margin-bottom: 0; }
.filter-bar .form-control { min-width: 160px; }

/* ─── Pagination ────────────────────────────────────────── */
.pagination { display: flex; gap: 4px; align-items: center; font-size: 13px; }
.pagination a, .pagination span { display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 7px; border: 1px solid var(--border); background: var(--surface); color: var(--text); }
.pagination a:hover { background: var(--bg); }
.pagination .active span { background: var(--navy-lt); color: #fff; border-color: var(--navy-lt); }
.pagination .disabled span { color: var(--muted); cursor: not-allowed; }

/* ─── Page title bar ────────────────────────────────────── */
.page-header { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; }
.page-header h1 { font-size: 20px; font-weight: 700; color: var(--text); flex: 1; }

/* ─── Responsive ────────────────────────────────────────── */
@media (max-width: 768px) {
  .sidebar { position: fixed; left: -100%; top: 0; bottom: 0; z-index: 100; transition: left .25s; }
  .sidebar.open { left: 0; }
  .form-row.cols-2, .form-row.cols-3 { grid-template-columns: 1fr; }
}

@media print {
  .shell { display: block; }
  .sidebar, .topbar { display: none; }
  .content { padding: 0; }
}
</style>
@stack('head')
</head>
<body>
<div class="shell">

  {{-- ── SIDEBAR ── --}}
  <aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
      <div class="logo-badge">Q</div>
      <div class="logo-text">QMMC GSS
        <span>General Services Section</span>
      </div>
    </div>

    @auth
      @if(auth()->user()->isAdmin())
        <div class="sidebar-section">
          <div class="sidebar-section-label">Admin</div>
          <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}"
               class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
              <svg class="icon" viewBox="0 0 16 16" fill="currentColor"><path d="M2 2h5v6H2V2zm7 0h5v4H9V2zM2 10h5v4H2v-4zm7 2h5v2H9v-2z"/></svg>
              Dashboard
            </a>
            <a href="{{ route('admin.janitors.index') }}"
               class="{{ request()->routeIs('admin.janitors.*') ? 'active' : '' }}">
              <svg class="icon" viewBox="0 0 16 16" fill="currentColor"><path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm5 6H3a5 5 0 0 1 10 0z"/></svg>
              Janitors
            </a>
            <a href="{{ route('admin.evaluations.index') }}"
               class="{{ request()->routeIs('admin.evaluations.*') ? 'active' : '' }}">
              <svg class="icon" viewBox="0 0 16 16" fill="currentColor"><path d="M5 3h8v1H5V3zm0 3h8v1H5V6zm0 3h5v1H5V9zM2 3a1 1 0 1 1 2 0 1 1 0 0 1-2 0zm0 3a1 1 0 1 1 2 0 1 1 0 0 1-2 0zm0 3a1 1 0 1 1 2 0 1 1 0 0 1-2 0z"/></svg>
              Evaluations
            </a>
            <a href="{{ route('admin.assignments.index') }}"
               class="{{ request()->routeIs('admin.assignments.*') ? 'active' : '' }}">
              <svg class="icon" viewBox="0 0 16 16" fill="currentColor"><path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0zM0 13a5 5 0 0 1 10 0H0zm13-5h2v2h-2v2h-2v-2h-2v-2h2V6h2v2z"/></svg>
              Assignments
            </a>
            <a href="{{ route('admin.users.index') }}"
               class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
              <svg class="icon" viewBox="0 0 16 16" fill="currentColor"><path d="M7 7a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 1a6 6 0 0 0-6 6h12a6 6 0 0 0-6-6zm5-2V5h1V4h-1V3h-1v1h-1v1h1v1h1z"/></svg>
              Accounts
            </a>
          </nav>
        </div>

      @elseif(auth()->user()->isEvaluator())
        <div class="sidebar-section">
          <div class="sidebar-section-label">Evaluator</div>
          <nav class="sidebar-nav">
            <a href="{{ route('evaluator.dashboard') }}"
               class="{{ request()->routeIs('evaluator.dashboard') ? 'active' : '' }}">
              <svg class="icon" viewBox="0 0 16 16" fill="currentColor"><path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm5 6H3a5 5 0 0 1 10 0z"/></svg>
              My Janitors
            </a>
            <a href="{{ route('evaluator.history') }}"
               class="{{ request()->routeIs('evaluator.history') ? 'active' : '' }}">
              <svg class="icon" viewBox="0 0 16 16" fill="currentColor"><path d="M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1zM7 4h2v5H7V4zm0 6h2v2H7v-2z"/></svg>
              My Submissions
            </a>
          </nav>
        </div>

      @elseif(auth()->user()->isJanitor())
        <div class="sidebar-section">
          <div class="sidebar-section-label">My Portal</div>
          <nav class="sidebar-nav">
            <a href="{{ route('janitor.dashboard') }}"
               class="{{ request()->routeIs('janitor.dashboard') ? 'active' : '' }}">
              <svg class="icon" viewBox="0 0 16 16" fill="currentColor"><path d="M2 2h5v6H2V2zm7 0h5v4H9V2zM2 10h5v4H2v-4zm7 2h5v2H9v-2z"/></svg>
              Dashboard
            </a>
            <a href="{{ route('janitor.history') }}"
               class="{{ request()->routeIs('janitor.history') ? 'active' : '' }}">
              <svg class="icon" viewBox="0 0 16 16" fill="currentColor"><path d="M5 3h8v1H5V3zm0 3h8v1H5V6zm0 3h5v1H5V9z"/></svg>
              My Evaluations
            </a>
          </nav>
        </div>
      @endif
    @endauth

    <div class="sidebar-footer">
      @auth
      <div class="user-card">
        <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
        <div class="info">
          <div class="uname">{{ auth()->user()->name }}</div>
          <div class="urole">{{ auth()->user()->role->name }}</div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" title="Sign out">&#x2192;</button>
        </form>
      </div>
      @endauth
    </div>
  </aside>

  {{-- ── MAIN ── --}}
  <div class="main">
    <header class="topbar">
      <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
      <div class="topbar-spacer"></div>
      <div style="font-size:12px; color: var(--muted)">
        {{ now()->format('l, F d Y') }}
      </div>
    </header>

    <main class="content">
      @if(session('success'))
        <div class="alert alert-success">✓ {{ session('success') }}</div>
      @endif
      @if(session('error'))
        <div class="alert alert-danger">✕ {{ session('error') }}</div>
      @endif
      @if($errors->any())
        <div class="alert alert-danger">
          <ul style="list-style:none; padding:0; margin:0;">
            @foreach($errors->all() as $error)
              <li>✕ {{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      @yield('content')
    </main>
  </div>

</div>
@stack('scripts')
</body>
</html>