<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'customer']);
    }

    public function edit()
    {
        return view('customer.profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $emailChanged = $request->string('email')->toString() !== $user->email;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'current_password' => [Rule::requiredIf($emailChanged), 'nullable', 'current_password'],
            'phone' => ['nullable', 'string', 'max:30'],
            'company' => ['nullable', 'string', 'max:255'],
            'street' => ['nullable', 'string', 'max:255'],
            'house_number' => ['nullable', 'string', 'max:30'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'city' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'kvk_number' => ['nullable', 'string', 'max:30'],
            'vat_number' => ['nullable', 'string', 'max:30'],
        ], [
            'current_password.required' => 'Vul je huidige wachtwoord in om je e-mailadres te wijzigen.',
            'current_password.current_password' => 'Het huidige wachtwoord is niet correct.',
        ]);

        unset($validated['current_password']);
        $user->update($validated);

        return back()->with('success', 'Je profielgegevens zijn bijgewerkt.');
    }

    public function updateLogo(Request $request)
    {
        $validated = $request->validate([
            'profile_logo' => ['nullable', 'required_without:remove_profile_logo', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_profile_logo' => ['nullable', 'boolean'],
        ], [
            'profile_logo.required_without' => 'Kies eerst een logo.',
            'profile_logo.image' => 'Kies een geldige afbeelding.',
            'profile_logo.max' => 'Het logo mag maximaal 2 MB zijn.',
        ]);
        $user = Auth::user();

        if ($user->profile_logo_path) {
            Storage::disk('public')->delete($user->profile_logo_path);
        }

        $path = $request->boolean('remove_profile_logo')
            ? null
            : $request->file('profile_logo')->store('customer-logos', 'public');
        $user->update(['profile_logo_path' => $path]);

        return back()->with('success', $path ? 'Je bedrijfslogo is bijgewerkt.' : 'Je bedrijfslogo is verwijderd.');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        Auth::user()->update(['password' => Hash::make($validated['password'])]);

        return back()->with('success', 'Je wachtwoord is gewijzigd.');
    }
}
