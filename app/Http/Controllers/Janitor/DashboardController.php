<?php

namespace App\Http\Controllers\Janitor;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Services\EvaluationService;

class DashboardController extends Controller
{
    public function __construct(private EvaluationService $service) {}

    public function index()
    {
        $janitor = auth()->user()->janitor;
        abort_unless($janitor, 403, 'No janitor profile linked to your account.');

        $recent = Evaluation::with('area')
            ->forJanitor($janitor->id)
            ->latest('eval_date')
            ->take(5)
            ->get();

        $average = Evaluation::forJanitor($janitor->id)->avg('total_score');

        return view('janitor.dashboard', compact('janitor', 'recent', 'average'));
    }

    public function history()
    {
        $janitor = auth()->user()->janitor;
        abort_unless($janitor, 403);

        $evaluations = Evaluation::with(['area', 'evaluator'])
            ->forJanitor($janitor->id)
            ->latest('eval_date')
            ->paginate(15);

        return view('janitor.history', compact('evaluations'));
    }

    public function show(Evaluation $evaluation)
    {
        $janitor = auth()->user()->janitor;
        abort_unless($janitor && $evaluation->janitor_id === $janitor->id, 403);
        $evaluation->load(['area', 'evaluator', 'scores']);

        return view('janitor.show', [
            'evaluation' => $evaluation,
            'sections'   => $this->service->getSections(),
        ]);
    }
}