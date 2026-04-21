<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Evaluation;
use App\Models\Janitor;
use App\Services\EvaluationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    public function index(Request $request)
    {
        $query = Evaluation::with(['janitor', 'area', 'evaluator'])->latest('eval_date');

        if ($request->filled('janitor_id')) $query->where('janitor_id', $request->janitor_id);
        if ($request->filled('area_id'))    $query->where('area_id', $request->area_id);
        if ($request->filled('date_from') || $request->filled('date_to')) {
            $query->inDateRange($request->date_from, $request->date_to);
        }
        if ($request->filled('rating')) $query->where('rating_label', $request->rating);

        $evaluations = $query->paginate(25)->withQueryString();
        $janitors    = Janitor::orderBy('name')->get(['id', 'name']);
        $areas       = Area::orderBy('name')->get(['id', 'name']);

        return view('admin.evaluations.index', compact('evaluations', 'janitors', 'areas'));
    }

    public function show(Evaluation $evaluation)
    {
        $evaluation->load(['janitor', 'area', 'evaluator', 'scores']);
        $service = app(EvaluationService::class);

        return view('admin.evaluations.show', [
            'evaluation' => $evaluation,
            'sections'   => $service->getSections(),
        ]);
    }

    public function exportPdf(Evaluation $evaluation)
    {
        $evaluation->load(['janitor', 'area', 'evaluator', 'scores']);
        $service = app(EvaluationService::class);

        $pdf = Pdf::loadView('admin.evaluations.pdf', [
            'evaluation' => $evaluation,
            'sections'   => $service->getSections(),
        ])->setPaper('a4', 'portrait');

        $filename = sprintf(
            'QMMC-Eval-%s-%s.pdf',
            str_replace(' ', '_', $evaluation->janitor->name),
            $evaluation->eval_date->format('Y-m-d')
        );

        return $pdf->download($filename);
    }

    public function exportListPdf(Request $request)
    {
        $query = Evaluation::with(['janitor', 'area', 'evaluator'])->latest('eval_date');

        if ($request->filled('janitor_id')) $query->where('janitor_id', $request->janitor_id);
        if ($request->filled('area_id'))    $query->where('area_id', $request->area_id);
        if ($request->filled('date_from') || $request->filled('date_to')) {
            $query->inDateRange($request->date_from, $request->date_to);
        }

        $evaluations = $query->get();

        $pdf = Pdf::loadView('admin.evaluations.pdf_list', compact('evaluations'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('QMMC-Evaluations-' . now()->format('Y-m-d') . '.pdf');
    }
}