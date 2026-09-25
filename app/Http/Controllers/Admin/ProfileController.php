<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('admin.profile', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user)],
            'phone' => 'nullable|string|max:30',
            'ticket_signature' => 'nullable|string|max:2000',
            'current_password' => 'nullable|required_with:password|string',
            'password' => 'nullable|string|min:12|confirmed',
        ]);

        if (($data['email'] !== $user->email || filled($data['password'] ?? null)) && ! Hash::check($data['current_password'] ?? '', $user->password)) {
            throw ValidationException::withMessages(['current_password' => 'Het huidige wachtwoord is niet correct.']);
        }

        if (blank($data['password'] ?? null)) unset($data['password']);
        unset($data['current_password']);
        $user->update($data);

        return back()->with('success', 'Uw profiel is bijgewerkt.');
    }
}
