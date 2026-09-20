<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServerConnection;
use App\Services\DirectAdminClient;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ServerConnectionController extends Controller
{
    public function index()
    {
        $connections = ServerConnection::withCount('services')->orderBy('name')->get();
        $providers = ServerConnection::PROVIDERS;

        return view('admin.server-connections', compact('connections', 'providers'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateConnection($request, true);
        $validated['verify_ssl'] = $request->boolean('verify_ssl');
        $validated['is_active'] = $request->boolean('is_active');
        ServerConnection::create($validated);

        return back()->with('success', 'Serverkoppeling is aangemaakt.');
    }

    public function update(Request $request, ServerConnection $serverConnection)
    {
        $validated = $this->validateConnection($request, false);
        if (blank($validated['password'] ?? null)) {
            unset($validated['password']);
        }
        $validated['verify_ssl'] = $request->boolean('verify_ssl');
        $validated['is_active'] = $request->boolean('is_active');
        $serverConnection->update($validated);

        return back()->with('success', 'Serverkoppeling is bijgewerkt.');
    }

    public function test(ServerConnection $serverConnection, DirectAdminClient $directAdmin)
    {
        try {
            match ($serverConnection->provider) {
                'directadmin' => $directAdmin->using($serverConnection)->testConnection(),
                default => throw new \RuntimeException('Deze provider wordt nog niet ondersteund.'),
            };
            $serverConnection->update([
                'last_tested_at' => now(),
                'last_test_status' => 'success',
                'last_test_message' => 'Verbinding en authenticatie zijn geslaagd.',
            ]);

            return back()->with('success', "Verbinding met {$serverConnection->name} is geslaagd.");
        } catch (\Throwable $exception) {
            $serverConnection->update([
                'last_tested_at' => now(),
                'last_test_status' => 'failed',
                'last_test_message' => $exception->getMessage(),
            ]);

            return back()->with('error', "Verbinding mislukt: {$exception->getMessage()}");
        }
    }

    public function destroy(ServerConnection $serverConnection)
    {
        if ($serverConnection->services()->exists()) {
            return back()->with('error', 'Deze serverkoppeling is nog aan producten gekoppeld.');
        }

        $serverConnection->delete();

        return back()->with('success', 'Serverkoppeling is verwijderd.');
    }

    private function validateConnection(Request $request, bool $passwordRequired): array
    {
        return $request->validate([
            'name' => 'required|string|max:100',
            'provider' => ['required', Rule::in(array_keys(ServerConnection::PROVIDERS))],
            'url' => 'required|url|max:255',
            'username' => 'required|string|max:100',
            'password' => ($passwordRequired ? 'required' : 'nullable') . '|string|max:500',
            'shared_ip' => 'required_if:provider,directadmin|nullable|ip',
            'verify_ssl' => 'boolean',
            'timeout' => 'required|integer|min:5|max:120',
            'is_active' => 'boolean',
        ]);
    }
}
