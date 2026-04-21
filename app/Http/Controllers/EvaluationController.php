<?php

namespace App\Http\Controllers;

use App\Models\JanitorialEvaluation;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    // Section definitions shared across views
    protected array $sectionA = [
        ['field' => 'a1',  'label' => "Cleans, disinfects, deodorizes sinks, toilets, urinals, partitions, and mirrors.", 'pts' => 5],
        ['field' => 'a2',  'label' => "Cleans and disinfects patients' rooms/offices and assigned high traffic areas.", 'pts' => 5],
        ['field' => 'a3',  'label' => "Cleans/disinfects ALL high touch points (bed handrails, corners, side tables, light switches, etc.).", 'pts' => 5],
        ['field' => 'a4',  'label' => "Clean walls (including doors and windows), ceilings, dusting, and removing cobwebs.", 'pts' => 5],
        ['field' => 'a5',  'label' => "Floor: Scrubs/disinfects, removes stains, sticky substances, and applies wax/polishes.", 'pts' => 5],
        ['field' => 'a6',  'label' => "Hallways/Stairways: Sweeps, mops, scrubs, and polishes tiled floors.", 'pts' => 5],
        ['field' => 'a7',  'label' => "Waste bins: Washes, decontaminates, and replaces color-coded plastic bags when needed.", 'pts' => 5],
        ['field' => 'a8',  'label' => "Collects and transports segregated and labelled/coded healthcare wastes correctly.", 'pts' => 5],
        ['field' => 'a9',  'label' => "Does general cleaning of room before admission of new patient and after discharge.", 'pts' => 5],
        ['field' => 'a10', 'label' => "Does damp dusting of fixed structures (window, ledges, panels, blinds, railing).", 'pts' => 5],
    ];

    protected array $sectionB = [
        ['field' => 'b1', 'label' => "Follows established safety procedures and precautions when performing tasks.", 'pts' => 5],
        ['field' => 'b2', 'label' => "Follows proper and safe techniques/methods including appropriate PPE usage.", 'pts' => 5],
        ['field' => 'b3', 'label' => "Wears prescribed uniform and ID.", 'pts' => 5],
        ['field' => 'b4', 'label' => "Demonstrates respectful, courteous, and considerate conduct.", 'pts' => 5],
        ['field' => 'b5', 'label' => "Shows initiative and positive attitude towards work.", 'pts' => 5],
        ['field' => 'b6', 'label' => "Arrives on time. Punctual.", 'pts' => 5],
    ];

    protected array $sectionC = [
        ['field' => 'c1', 'label' => "Follows proper healthcare waste segregation and disposal.", 'pts' => 10],
        ['field' => 'c2', 'label' => "Ensures established infection control and precaution protocols are followed.", 'pts' => 10],
    ];

    public function create()
    {
        return view('evaluation.form', [
            'sectionA' => $this->sectionA,
            'sectionB' => $this->sectionB,
            'sectionC' => $this->sectionC,
            'evaluation' => null,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'janitor_name' => 'required|string|max:200',
            'area'         => 'required|string|max:200',
            'date'         => 'required|date',
            'time'         => 'required',
            'evaluated_by' => 'nullable|string|max:200',
            'noted_by'     => 'nullable|string|max:200',
            'comments'     => 'nullable|string',
        ]);

        // Collect checkbox fields (unchecked = not sent = false)
        $checkboxFields = ['a1','a2','a3','a4','a5','a6','a7','a8','a9','a10',
                           'b1','b2','b3','b4','b5','b6','c1','c2'];
        foreach ($checkboxFields as $field) {
            $validated[$field] = $request->has($field);
        }

        $evaluation = JanitorialEvaluation::create($validated);

        return redirect()->route('evaluation.result', $evaluation->id)
                         ->with('success', 'Evaluation submitted successfully!');
    }

    public function result($id)
    {
        $evaluation = JanitorialEvaluation::findOrFail($id);
        return view('evaluation.result', compact('evaluation'));
    }

    public function index()
    {
        $evaluations = JanitorialEvaluation::latest()->get();
        return view('evaluation.list', compact('evaluations'));
    }
}
