<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    public function index()
    {
        $this->authorizeOwner();
        $staff = User::whereIn('role', ['owner', 'admin', 'employee'])->orderByRaw("CASE role WHEN 'owner' THEN 1 WHEN 'admin' THEN 2 ELSE 3 END")->orderBy('name')->get();

        return view('admin.settings.staff', compact('staff'));
    }

    public function store(Request $request)
    {
        $this->authorizeOwner();
        $data = $request->validate([
            'name' => 'required|string|max:255', 'email' => 'required|email|max:255|unique:users,email',
            'role' => ['required', Rule::in(['admin', 'employee'])], 'password' => 'required|string|min:12|confirmed',
        ]);
        $data['is_active'] = true;
        $data['email_verified_at'] = now();
        User::create($data);

        return back()->with('success', 'Medewerkeraccount is aangemaakt.');
    }

    public function update(Request $request, User $staff)
    {
        $this->authorizeOwner();
        abort_unless(in_array($staff->role, ['admin', 'employee'], true), 403);
        $data = $request->validate([
            'name' => 'required|string|max:255', 'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($staff)],
            'role' => ['required', Rule::in(['admin', 'employee'])], 'is_active' => 'boolean', 'password' => 'nullable|string|min:12|confirmed',
        ]);
        $data['is_active'] = $request->boolean('is_active');
        if (blank($data['password'] ?? null)) unset($data['password']);
        $staff->update($data);

        return back()->with('success', 'Medewerkeraccount is bijgewerkt.');
    }

    public function destroy(User $staff)
    {
        $this->authorizeOwner();
        abort_if($staff->isOwner() || $staff->is(auth()->user()), 403);
        abort_unless(in_array($staff->role, ['admin', 'employee'], true), 404);
        $staff->delete();

        return back()->with('success', 'Medewerkeraccount is verwijderd.');
    }

    private function authorizeOwner(): void
    {
        abort_unless(auth()->user()->isOwner(), 403);
    }
}
