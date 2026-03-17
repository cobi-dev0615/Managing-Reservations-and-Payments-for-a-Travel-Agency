<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->paginate(20);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = User::ROLES;
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
            'role' => ['required', Rule::in(array_keys(User::ROLES))],
        ]);

        // Users created by admin are auto-approved
        $validated['status'] = User::STATUS_APPROVED;

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'Usuario criado com sucesso.');
    }

    public function edit(User $user)
    {
        $roles = User::ROLES;
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'role' => ['required', Rule::in(array_keys(User::ROLES))],
            'status' => ['required', Rule::in(array_keys(User::STATUSES))],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->status = $validated['status'];

        if (!empty($validated['password'])) {
            $user->password = $validated['password'];
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'Usuario atualizado com sucesso.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Voce nao pode excluir sua propria conta.');
        }

        if ($user->isAdmin() && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Nao e possivel excluir o unico administrador.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuario excluido com sucesso.');
    }

    public function approve(User $user)
    {
        $user->update(['status' => User::STATUS_APPROVED]);

        ActivityLog::log('aprovou', 'User', $user->id, ['name' => $user->name]);

        return back()->with('success', "Usuario {$user->name} aprovado com sucesso.");
    }

    public function suspend(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Voce nao pode suspender sua propria conta.');
        }

        if ($user->isAdmin()) {
            return back()->with('error', 'Nao e possivel suspender um administrador.');
        }

        $user->update(['status' => User::STATUS_SUSPENDED]);

        ActivityLog::log('suspendeu', 'User', $user->id, ['name' => $user->name]);

        return back()->with('success', "Usuario {$user->name} suspenso com sucesso.");
    }
}
