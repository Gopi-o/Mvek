<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.index', [
            'users' => User::latest()->paginate(20),
            'roles' => ['user' => 'Покупатель', 'manager' => 'Менеджер', 'admin' => 'Администратор'],
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => ['required', Rule::in(['user', 'manager', 'admin'])],
        ]);

        if ($user->is($request->user()) && $validated['role'] !== 'admin') {
            return back()->withErrors(['role' => 'Нельзя снять роль администратора со своей учётной записи.']);
        }

        if ($user->role === 'admin' && $validated['role'] !== 'admin' && User::where('role', 'admin')->count() === 1) {
            return back()->withErrors(['role' => 'В системе должен остаться хотя бы один администратор.']);
        }

        $user->update($validated);

        return to_route('admin.users.index')->with('success', 'Роль пользователя обновлена.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->is($request->user())) {
            return back()->withErrors(['user' => 'Нельзя удалить собственную учётную запись из панели.']);
        }

        if ($user->role === 'admin' && User::where('role', 'admin')->count() === 1) {
            return back()->withErrors(['user' => 'Нельзя удалить последнего администратора.']);
        }

        $user->delete();

        return to_route('admin.users.index')->with('success', 'Пользователь удалён.');
    }
}
