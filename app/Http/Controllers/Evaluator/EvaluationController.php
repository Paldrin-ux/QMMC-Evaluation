<?php

namespace App\Http\Controllers\Evaluator;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\Janitor;
use App\Services\EvaluationService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EvaluationController extends Controller
{
    public function __construct(private EvaluationService $service) {}

    /**
     * Dashboard — show all janitors in the evaluator's assigned areas.
     */
    public function assignedJanitors()
    {
        $evaluator = auth()->user()->load('assignedAreas');
        $areaIds   = $evaluator->assignedAreas->pluck('id');

        $janitors = Janitor::whereHas('areas', fn($q) => $q->whereIn('areas.id', $areaIds))
            ->with('areas')
            ->where('is_active', true)
            ->orderBy('name')
            ->paginate(20);

        $assignedAreas = $evaluator->assignedAreas;

        $janitorIds = $janitors->getCollection()->pluck('id')->all();
        $recentEvaluations = $janitorIds
            ? Evaluation::query()
                ->where('evaluated_by', auth()->id())
                ->whereIn('janitor_id', $janitorIds)
                ->where('created_at', '>=', now()->subHours(24))
                ->get()
                ->groupBy('janitor_id')
            : collect();

        // Keep only the latest evaluation within the last 24 hours per janitor.
        $recentEvaluationByJanitor = $recentEvaluations->map(function ($items) {
            return $items->sortByDesc('created_at')->first();
        });

        return view(
            'evaluator.janitors',
            compact('janitors', 'assignedAreas', 'recentEvaluationByJanitor')
        );
    }

    /**
     * Show evaluation form. Evaluator can evaluate any janitor
     * whose area overlaps with the evaluator's assigned areas.
     */
    public function create(Janitor $janitor)
    {
        $evaluator = auth()->user()->load('assignedAreas');
        $areaIds   = $evaluator->assignedAreas->pluck('id');

        // Check janitor shares at least one area with this evaluator
        $sharedAreas = $janitor->areas->whereIn('id', $areaIds);

        abort_unless(
            $sharedAreas->isNotEmpty(),
            403,
            'You are not assigned to evaluate this janitor.'
        );

        // Only show areas the evaluator is assigned to for this janitor
        $areas    = $sharedAreas;
        $sections = $this->service->getSections();

        return view('evaluator.form', compact('janitor', 'areas', 'sections'));
    }

    /**
     * Store the evaluation.
     */
    public function store(Request $request, Janitor $janitor)
    {
        $evaluator = auth()->user()->load('assignedAreas');
        $areaIds   = $evaluator->assignedAreas->pluck('id');
        $sharedAreas = $janitor->areas->whereIn('id', $areaIds);

        abort_unless($sharedAreas->isNotEmpty(), 403);

        $validated = $request->validate([
            'area_id'   => ['required', Rule::in($sharedAreas->pluck('id')->all())],
            'eval_date' => 'required|date',
            'eval_time' => 'required',
            'noted_by'  => 'nullable|string|max:200',
            'comments'  => 'nullable|string',
        ]);

        $allFields = array_merge(
            array_column(EvaluationService::SECTION_A, 'field'),
            array_column(EvaluationService::SECTION_B, 'field'),
            array_column(EvaluationService::SECTION_C, 'field')
        );

        $checkboxes = [];
        foreach ($allFields as $field) {
            $checkboxes[$field] = $request->boolean($field);
        }

        $evaluation = $this->service->store(
            array_merge($validated, [
                'evaluated_by' => auth()->id(),
                'janitor_id'   => $janitor->id,
            ]),
            $checkboxes
        );

        return redirect()->route('evaluator.history')
            ->with('success', "Evaluation for {$janitor->name} submitted. Score: {$evaluation->total_score}/100.");
    }

    /**
     * Evaluator's own submission history.
     */
    public function history(Request $request)
    {
        $query = Evaluation::with(['janitor', 'area'])
            ->forEvaluator(auth()->id())
            ->latest('eval_date');

        if ($request->filled('search')) {
            $query->whereHas('janitor', fn($q) => $q->where('name', 'like', "%{$request->search}%"));
        }

        $evaluations = $query->paginate(20)->withQueryString();

        return view('evaluator.history', compact('evaluations'));
    }

    /**
     * View a single evaluation (read-only).
     */
    public function show(Evaluation $evaluation)
    {
        abort_unless($evaluation->evaluated_by === auth()->id(), 403);
        $evaluation->load(['janitor', 'area', 'scores']);

        return view('evaluator.show', [
            'evaluation' => $evaluation,
            'sections'   => $this->service->getSections(),
        ]);
    }
}