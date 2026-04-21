<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Evaluator;
use App\Http\Controllers\Janitor;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
// ─── Auth ────────────────────────────────────────────────────────────────────

Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout',[AuthController::class, 'logout'])->name('logout');
Route::get('/sso-login', [App\Http\Controllers\SsoController::class, 'handle'])->name('sso.login');
// ─── Admin ───────────────────────────────────────────────────────────────────

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

        // Janitor management (CRUD)
        Route::resource('janitors', Admin\JanitorController::class);

        // Evaluation records + PDF export
        Route::get('evaluations',              [Admin\EvaluationController::class, 'index'])         ->name('evaluations.index');
        Route::get('evaluations/export-pdf',   [Admin\EvaluationController::class, 'exportListPdf']) ->name('evaluations.export_list_pdf');
        Route::get('evaluations/{evaluation}', [Admin\EvaluationController::class, 'show'])          ->name('evaluations.show');
        Route::get('evaluations/{evaluation}/pdf', [Admin\EvaluationController::class, 'exportPdf'])->name('evaluations.pdf');

        // Account management
        Route::resource('users', Admin\UserController::class)->except(['show']);
        Route::patch('users/{user}/toggle-status', [Admin\UserController::class, 'toggleStatus'])->name('users.toggle_status');

        // Evaluator ↔ Janitor assignments
        Route::get('assignments',  [Admin\AssignmentController::class, 'index']) ->name('assignments.index');
        Route::post('assignments', [Admin\AssignmentController::class, 'update'])->name('assignments.update');
    });

// ─── Evaluator ───────────────────────────────────────────────────────────────

Route::prefix('evaluator')
    ->name('evaluator.')
    ->middleware(['auth', 'role:evaluator'])
    ->group(function () {

        Route::get('/',          [Evaluator\EvaluationController::class, 'assignedJanitors'])->name('dashboard');
        Route::get('/history',   [Evaluator\EvaluationController::class, 'history'])         ->name('history');
        Route::get('/evaluate/{janitor}',  [Evaluator\EvaluationController::class, 'create'])->name('evaluate.create');
        Route::post('/evaluate/{janitor}', [Evaluator\EvaluationController::class, 'store']) ->name('evaluate.store');
        Route::get('/result/{evaluation}', [Evaluator\EvaluationController::class, 'show'])  ->name('evaluate.show');
    });

// ─── Janitor portal ──────────────────────────────────────────────────────────

Route::prefix('portal')
    ->name('janitor.')
    ->middleware(['auth', 'role:janitor'])
    ->group(function () {

        Route::get('/',                    [Janitor\DashboardController::class, 'index'])  ->name('dashboard');
        Route::get('/history',             [Janitor\DashboardController::class, 'history'])->name('history');
        Route::get('/result/{evaluation}', [Janitor\DashboardController::class, 'show'])   ->name('show');
    });

// ─── Root redirect based on role ─────────────────────────────────────────────

Route::get('/', function () {
    if (! Auth::check()) return redirect()->route('login');

    return match (Auth::user()->role->slug) {
        'admin'     => redirect()->route('admin.dashboard'),
        'evaluator' => redirect()->route('evaluator.dashboard'),
        'janitor'   => redirect()->route('janitor.dashboard'),
        default     => redirect()->route('login'),
    };
})->middleware('web');

Route::any('/{id}', function ($id) {
    dd('User ID from intranet: ' . $id, request()->all(), $_SESSION ?? 'no session');
})->where('id', '[0-9]+');

// Catches intranet button click e.g. /743
Route::any('/{id}', [App\Http\Controllers\SsoController::class, 'handleIntranet'])
    ->where('id', '[0-9]+');

    // Secret admin login page - only you know this URL
Route::get('/qmmc-admin-2026', function () {
    return view('auth.login');
})->name('admin.secret.login');
