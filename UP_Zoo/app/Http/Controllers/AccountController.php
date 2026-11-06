<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    public function show(Request $request)
    {
        return view('pages.account', [
            'orders' => $request->user()->orders()->with('items')->latest()->get(),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($request->user()->id)],
        ]);

        $request->user()->update($validated);

        return to_route('account')->with('success', 'Профиль обновлён.');
    }
}
