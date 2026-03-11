<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class TourController extends Controller
{
    public function index(Request $request)
    {
        $query = Tour::query();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $perPage = in_array((int) $request->input('per_page'), [10, 25, 50, 100]) ? (int) $request->input('per_page') : 10;
        $tours = $query->orderBy('name')->paginate($perPage)->withQueryString();

        return view('tours.index', compact('tours'));
    }

    public function create()
    {
        return view('tours.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'code'             => 'required|string|max:255|unique:tours,code',
            'type'             => 'required|in:grupo,privado,agencia,influencer',
            'default_currency' => 'nullable|string|max:10',
            'notes'            => 'nullable|string',
            'status'           => 'nullable|in:ativo,inativo',
            'max_travelers'    => 'nullable|integer|min:1',
        ]);

        $tour = Tour::create($validated);

        ActivityLog::log('criou', 'Tour', $tour->id, ['name' => $tour->name]);

        return redirect()->route('tours.show', $tour)->with('success', 'Tour criado com sucesso.');
    }

    public function show(Tour $tour)
    {
        $tour->load('bookings.client');

        return view('tours.show', compact('tour'));
    }

    public function edit(Tour $tour)
    {
        return view('tours.edit', compact('tour'));
    }

    public function update(Request $request, Tour $tour)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'code'             => 'required|string|max:255|unique:tours,code,' . $tour->id,
            'type'             => 'required|in:grupo,privado,agencia,influencer',
            'default_currency' => 'nullable|string|max:10',
            'notes'            => 'nullable|string',
            'status'           => 'nullable|in:ativo,inativo',
            'max_travelers'    => 'nullable|integer|min:1',
        ]);

        $tour->update($validated);

        ActivityLog::log('atualizou', 'Tour', $tour->id, ['name' => $tour->name]);

        return redirect()->route('tours.show', $tour)->with('success', 'Tour atualizado com sucesso.');
    }

    public function toggleStatus(Tour $tour)
    {
        $tour->status = $tour->status === 'ativo' ? 'inativo' : 'ativo';
        $tour->save();

        ActivityLog::log('alterou status', 'Tour', $tour->id, [
            'name'   => $tour->name,
            'status' => $tour->status,
        ]);

        return redirect()->back()->with('success', 'Status do tour alterado para ' . $tour->status . '.');
    }
}
