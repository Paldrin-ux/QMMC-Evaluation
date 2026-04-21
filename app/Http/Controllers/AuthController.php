<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
  public function showLogin()
{
    if (Auth::check()) {
        return $this->redirectByRole(Auth::user());
    }
    return view('auth.login');
}

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

        $user = Auth::user();

        if (! $user->is_active) {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Your account is deactivated. Please contact an administrator.',
            ]);
        }

        $request->session()->regenerate();

        return $this->redirectByRole($user);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    private function redirectByRole($user)
    {
        return match ($user->role->slug) {
            'admin'     => redirect()->intended(route('admin.dashboard')),
            'evaluator' => redirect()->intended(route('evaluator.dashboard')),
            'janitor'   => redirect()->intended(route('janitor.dashboard')),
            default     => redirect()->route('login'),
        };
    }
}