<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            // Check if user account is approved
            if ($user->isPending()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors([
                    'status' => 'Sua conta esta aguardando aprovacao do administrador.',
                ])->onlyInput('email');
            }

            if ($user->isSuspended()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors([
                    'status' => 'Sua conta foi suspensa. Entre em contato com o administrador.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'Credenciais invalidas.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        // Exclude admin from self-registration roles
        $roles = collect(User::ROLES)->except(User::ROLE_ADMIN)->toArray();
        return view('auth.register', compact('roles'));
    }

    public function register(Request $request)
    {
        $allowedRoles = [User::ROLE_MANAGER, User::ROLE_VIEWER];

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
            'role' => ['required', Rule::in($allowedRoles)],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => $validated['role'],
            'status' => User::STATUS_PENDING,
        ]);

        return redirect()->route('login')->with('info', 'Conta criada com sucesso! Aguarde a aprovacao do administrador para acessar o sistema.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
