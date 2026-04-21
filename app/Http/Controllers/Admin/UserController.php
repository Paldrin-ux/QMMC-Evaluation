<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Janitor;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('role')->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('role')) {
            $query->whereHas('role', fn($q) => $q->where('slug', $request->role));
        }

        $users = $query->paginate(20)->withQueryString();
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::all();
        $areas = Area::orderBy('name')->get();
        $janitors = Janitor::active()->doesntHave('userAccount')->orderBy('name')->get();
        return view('admin.users.create', compact('roles', 'areas', 'janitors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:200',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|string|min:8|confirmed',
            'role_id'    => 'required|exists:roles,id',
            'is_active'  => 'boolean',
            'janitor_id' => 'nullable|exists:janitors,id',
            'area_ids'   => 'nullable|array',
            'area_ids.*' => 'exists:areas,id',
        ]);

        $user = User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'password'  => Hash::make($validated['password']),
            'role_id'   => $validated['role_id'],
            'is_active' => $validated['is_active'] ?? true,
        ]);

        // Assign areas if this is an evaluator
        if (! empty($validated['area_ids'])) {
            $user->assignedAreas()->sync($validated['area_ids']);
        }

        // Link janitor profile if this is a janitor account
        if (! empty($validated['janitor_id'])) {
            Janitor::where('id', $validated['janitor_id'])->update(['user_id' => $user->id]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', "User \"{$user->name}\" created successfully.");
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $areas = Area::orderBy('name')->get();
        $assignedAreaIds = $user->assignedAreas->pluck('id')->toArray();
        return view('admin.users.edit', compact('user', 'roles', 'areas', 'assignedAreaIds'));
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name'       => 'required|string|max:200',
            'email'      => "required|email|unique:users,email,{$user->id}",
            'role_id'    => 'required|exists:roles,id',
            'is_active'  => 'boolean',
            'area_ids'   => 'nullable|array',
            'area_ids.*' => 'exists:areas,id',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'string|min:8|confirmed';
        }

        $validated = $request->validate($rules);

        $data = [
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'role_id'   => $validated['role_id'],
            'is_active' => $validated['is_active'] ?? false,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // Sync areas
        $user->assignedAreas()->sync($validated['area_ids'] ?? []);

        return redirect()->route('admin.users.index')
            ->with('success', "User \"{$user->name}\" updated successfully.");
    }

    public function toggleStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'You cannot deactivate your own account.']);
        }

        $user->update(['is_active' => ! $user->is_active]);
        $status = $user->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Account \"{$user->name}\" has been {$status}.");
    }
}