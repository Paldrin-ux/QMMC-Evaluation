<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Janitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JanitorController extends Controller
{
    public function index(Request $request)
    {
        $query = Janitor::with('areas')->latest();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->input('status') === 'active');
        }

        $janitors = $query->paginate(20)->withQueryString();
        $areas    = Area::orderBy('name')->get();

        return view('admin.janitors.index', compact('janitors', 'areas'));
    }

    public function create()
    {
        $areas = Area::orderBy('name')->get();
        $nextEmployeeId = $this->generateNextEmployeeId();
        return view('admin.janitors.create', compact('areas', 'nextEmployeeId'));
    }

    public function store(Request $request)
    {
        // Normalize empty input so `nullable` works as intended.
        if (! $request->filled('employee_id')) {
            $request->merge(['employee_id' => null]);
        }

        $validated = $request->validate([
            'name'        => 'required|string|max:200',
            'employee_id' => 'nullable|string|max:50|unique:janitors,employee_id',
            'is_active'   => 'boolean',
            'area_ids'    => 'required|array|min:1',
            'area_ids.*'  => 'exists:areas,id',
        ]);

        $employeeId = $validated['employee_id'] ?? null;
        if (is_null($employeeId) || $employeeId === '') {
            $employeeId = $this->generateNextEmployeeId();
        }

        $janitor = Janitor::create([
            'name'        => $validated['name'],
            'employee_id' => $employeeId,
            'is_active'   => $validated['is_active'] ?? true,
        ]);

        $janitor->areas()->sync($validated['area_ids']);

        return redirect()->route('admin.janitors.index')
            ->with('success', "Janitor \"{$janitor->name}\" created successfully.");
    }

    public function edit(Janitor $janitor)
    {
        $areas           = Area::orderBy('name')->get();
        $assignedAreaIds = $janitor->areas->pluck('id')->toArray();

        return view('admin.janitors.edit', compact('janitor', 'areas', 'assignedAreaIds'));
    }

    public function update(Request $request, Janitor $janitor)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:200',
            'employee_id' => "nullable|string|max:50|unique:janitors,employee_id,{$janitor->id}",
            'is_active'   => 'boolean',
            'area_ids'    => 'required|array|min:1',
            'area_ids.*'  => 'exists:areas,id',
        ]);

        $janitor->update([
            'name'        => $validated['name'],
            'employee_id' => $validated['employee_id'] ?? null,
            'is_active'   => $validated['is_active'] ?? false,
        ]);

        $janitor->areas()->sync($validated['area_ids']);

        return redirect()->route('admin.janitors.index')
            ->with('success', "Janitor \"{$janitor->name}\" updated successfully.");
    }

    public function destroy(Janitor $janitor)
    {
        if ($janitor->evaluations()->exists()) {
            $janitor->update(['is_active' => false]);
            $message = "Janitor \"{$janitor->name}\" has evaluations on record — deactivated instead of deleted.";
        } else {
            $name = $janitor->name;
            $janitor->delete();
            $message = "Janitor \"{$name}\" deleted.";
        }

        return redirect()->route('admin.janitors.index')->with('success', $message);
    }

    private function generateNextEmployeeId(): string
    {
        // If employee_id is numeric strings like "1", "2", "3", this will generate the next one.
        $max = DB::table('janitors')->selectRaw('MAX(CAST(employee_id AS UNSIGNED)) as max_emp')->value('max_emp');
        $next = (int) ($max ?? 0) + 1;
        if ($next < 1) $next = 1;

        $candidate = (string) $next;
        while (Janitor::where('employee_id', $candidate)->exists()) {
            $next++;
            $candidate = (string) $next;
        }

        return $candidate;
    }
}