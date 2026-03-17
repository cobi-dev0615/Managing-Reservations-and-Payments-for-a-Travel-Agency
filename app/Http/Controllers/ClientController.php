<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Booking;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::withCount('bookings');

        // Viewers can only see clients from their own bookings
        if (auth()->user()->isViewer()) {
            $myClientIds = Booking::where('created_by', auth()->id())->pluck('client_id')->unique();
            $query->whereIn('id', $myClientIds);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $perPage = in_array((int) $request->input('per_page'), [10, 25, 50, 100]) ? (int) $request->input('per_page') : 10;
        $clients = $query->orderBy('name')->paginate($perPage)->withQueryString();

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

        ActivityLog::log(__('messages.log_created'), 'Client', $client->id, ['name' => $client->name]);

        return redirect()->route('clients.show', $client)->with('success', __('messages.client_created'));
    }

    public function show(Client $client)
    {
        if (auth()->user()->isViewer()) {
            $hasAccess = Booking::where('created_by', auth()->id())->where('client_id', $client->id)->exists();
            if (!$hasAccess) {
                abort(403, __('messages.unauthorized'));
            }
        }

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

        ActivityLog::log(__('messages.log_updated'), 'Client', $client->id, ['name' => $client->name]);

        return redirect()->route('clients.show', $client)->with('success', __('messages.client_updated'));
    }

    public function destroy(Client $client)
    {
        $name = $client->name;

        $client->delete();

        ActivityLog::log(__('messages.log_deleted'), 'Client', null, ['name' => $name]);

        return redirect()->route('clients.index')->with('success', __('messages.client_deleted'));
    }
}
