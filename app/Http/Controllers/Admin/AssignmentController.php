<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index()
    {
        $evaluatorRoleId = Role::where('slug', 'evaluator')->value('id');

        $evaluators = User::where('role_id', $evaluatorRoleId)
            ->where('is_active', true)
            ->with(['assignedAreas'])
            ->orderBy('name')
            ->get();

        $areas = Area::orderBy('name')->get();

        return view('admin.assignments.index', compact('evaluators', 'areas'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'evaluator_id'  => 'required|exists:users,id',
            'area_ids'      => 'nullable|array',
            'area_ids.*'    => 'exists:areas,id',
        ]);

        $evaluator = User::findOrFail($validated['evaluator_id']);
        abort_unless($evaluator->role->slug === 'evaluator', 422, 'Selected user is not an evaluator.');

        $evaluator->assignedAreas()->sync($validated['area_ids'] ?? []);

        return back()->with('success', "Areas updated for {$evaluator->name}.");
    }
}