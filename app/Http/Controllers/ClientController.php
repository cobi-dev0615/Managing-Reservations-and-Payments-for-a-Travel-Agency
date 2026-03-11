<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::withCount('bookings');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $clients = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'notes' => 'nullable|string',
        ]);

        $client = Client::create($validated);

        ActivityLog::log('criou', 'Client', $client->id, ['name' => $client->name]);

        return redirect()->route('clients.show', $client)->with('success', 'Cliente criado com sucesso.');
    }

    public function show(Client $client)
    {
        $client->load('bookings.tour');

        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'notes' => 'nullable|string',
        ]);

        $client->update($validated);

        ActivityLog::log('atualizou', 'Client', $client->id, ['name' => $client->name]);

        return redirect()->route('clients.show', $client)->with('success', 'Cliente atualizado com sucesso.');
    }

    public function destroy(Client $client)
    {
        $name = $client->name;

        $client->delete();

        ActivityLog::log('excluiu', 'Client', null, ['name' => $name]);

        return redirect()->route('clients.index')->with('success', 'Cliente excluído com sucesso.');
    }
}
