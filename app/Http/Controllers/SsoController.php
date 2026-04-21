<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SsoController extends Controller
{
    public function handleIntranet(Request $request, $id)
    {
        // 1. Look up user in intranet database
        $intranetUser = DB::connection('intranet')
            ->table('bvflh_users')
            ->where('id', $id)
            ->first();

        if (! $intranetUser) {
            abort(403, 'User not found in intranet. Please make sure you are logged in.');
        }

        // 2. Everyone from intranet = evaluator
        $role = Role::where('slug', 'evaluator')->firstOrFail();

        // 3. Find or create the user in Laravel
        $user = User::updateOrCreate(
            ['email' => $intranetUser->email],
            [
                'name'      => $intranetUser->name,
                'role_id'   => $role->id,
                'is_active' => true,
                'password'  => bcrypt(Str::random(32)),
            ]
        );

        // 4. Log them in
        Auth::login($user);
        $request->session()->regenerate();

        // 5. Go to evaluator dashboard
        return redirect()->route('evaluator.dashboard');
    }
}