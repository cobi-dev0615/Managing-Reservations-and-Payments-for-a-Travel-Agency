<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Client;
use App\Models\Tour;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['client', 'tour']);

        // Viewers can only see their own bookings
        if (auth()->user()->isViewer()) {
            $query->where('created_by', auth()->id());
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tour_id')) {
            $query->where('tour_id', $request->tour_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('client', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('tour', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                })
                ->orWhere('tour_manual', 'like', "%{$search}%");
            });
        }

        $perPage = in_array((int) $request->input('per_page'), [10, 25, 50, 100]) ? (int) $request->input('per_page') : 10;
        $bookings = $query->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();
        $tours = Tour::orderBy('name')->get();

        return view('bookings.index', compact('bookings', 'tours'));
    }

    public function create(Request $request)
    {
        $tours = Tour::where('status', 'ativo')->orderBy('name')->get();
        $clients = Client::orderBy('name')->get();
        $selectedClientId = $request->get('client_id');

        return view('bookings.create', compact('tours', 'clients', 'selectedClientId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id'      => 'required|exists:clients,id',
            'tour_id'        => 'nullable|exists:tours,id',
            'tour_manual'    => 'required_without:tour_id|nullable|string|max:255',
            'start_date'     => 'required|date',
            'currency'       => 'required|string|in:BRL,USD,EUR,ZAR',
            'total_value'    => 'required|numeric|min:0',
            'discount_notes' => 'nullable|string',
            'num_travelers'  => 'required|integer|min:1',
            'status'         => 'nullable|in:confirmado,pendente,cancelado,concluido',
            'notes'          => 'nullable|string',
        ]);

        if (!empty($validated['tour_id'])) {
            $validated['tour_manual'] = null;
        }

        $validated['status'] = $validated['status'] ?? 'pendente';
        $validated['created_by'] = auth()->id();

        $booking = Booking::create($validated);

        // Auto-generate installments
        $numInstallments = (int) $request->input('num_installments', 0);
        if ($numInstallments > 0) {
            $installmentAmount = round($booking->total_value / $numInstallments, 2);
            $lastInstallmentAmount = $booking->total_value - ($installmentAmount * ($numInstallments - 1));
            $paymentMethod = $request->input('installment_payment_method', 'pix');
            $startDate = Carbon::today();

            for ($i = 1; $i <= $numInstallments; $i++) {
                $dueDate = $startDate->copy()->addMonths($i - 1);
                $amount = ($i === $numInstallments) ? $lastInstallmentAmount : $installmentAmount;

                $installment = $booking->installments()->create([
                    'installment_number' => $i,
                    'amount'             => $amount,
                    'due_date'           => $dueDate,
                    'payment_method'     => $paymentMethod,
                    'status'             => 'pendente',
                ]);

                $installment->status = $installment->resolveStatus();
                $installment->save();
            }
        }

        ActivityLog::log(__('messages.log_created'), 'Booking', $booking->id, [
            'client' => $booking->client->name ?? '',
            'tour'   => $booking->tour_name,
        ]);

        return redirect()->route('bookings.show', $booking)->with('success', __('messages.booking_created'));
    }

    public function show(Booking $booking)
    {
        if (auth()->user()->isViewer() && $booking->created_by !== auth()->id()) {
            abort(403, __('messages.unauthorized'));
        }

        $booking->load(['installments' => function ($q) {
            $q->orderBy('installment_number');
        }, 'client', 'tour']);

        return view('bookings.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        $tours = Tour::where('status', 'ativo')->orderBy('name')->get();
        $clients = Client::orderBy('name')->get();

        return view('bookings.edit', compact('booking', 'tours', 'clients'));
    }

    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'client_id'      => 'required|exists:clients,id',
            'tour_id'        => 'nullable|exists:tours,id',
            'tour_manual'    => 'required_without:tour_id|nullable|string|max:255',
            'start_date'     => 'required|date',
            'currency'       => 'required|string|in:BRL,USD,EUR,ZAR',
            'total_value'    => 'required|numeric|min:0',
            'discount_notes' => 'nullable|string',
            'num_travelers'  => 'required|integer|min:1',
            'status'         => 'nullable|in:confirmado,pendente,cancelado,concluido',
            'notes'          => 'nullable|string',
        ]);

        if (!empty($validated['tour_id'])) {
            $validated['tour_manual'] = null;
        }

        $booking->update($validated);

        ActivityLog::log(__('messages.log_updated'), 'Booking', $booking->id, [
            'client' => $booking->client->name ?? '',
            'tour'   => $booking->tour_name,
        ]);

        return redirect()->route('bookings.show', $booking)->with('success', __('messages.booking_updated'));
    }

    public function destroy(Booking $booking)
    {
        $details = [
            'client' => $booking->client->name ?? '',
            'tour'   => $booking->tour_name,
        ];

        $booking->delete();

        ActivityLog::log(__('messages.log_deleted'), 'Booking', null, $details);

        return redirect()->route('bookings.index')->with('success', __('messages.booking_deleted'));
    }
}
