<?php

namespace App\Http\Controllers;

use App\Models\Installment;
use App\Models\Tour;
use Illuminate\Http\Request;

class PaymentCockpitController extends Controller
{
    public function index(Request $request)
    {
        $query = Installment::with(['booking.client', 'booking.tour']);

        // Resolve statuses before filtering
        // We load all non-pago installments to update their resolved status
        $installmentsToResolve = Installment::where('status', '!=', 'pago')->get();
        foreach ($installmentsToResolve as $installment) {
            $resolved = $installment->resolveStatus();
            if ($installment->status !== $resolved) {
                $installment->status = $resolved;
                $installment->save();
            }
        }

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('booking.client', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('booking.tour', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('booking', function ($q2) use ($search) {
                    $q2->where('tour_manual', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('date_from')) {
            $query->where('due_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('due_date', '<=', $request->date_to);
        }

        if ($request->filled('tour_id')) {
            $query->whereHas('booking', function ($q) use ($request) {
                $q->where('tour_id', $request->tour_id);
            });
        }

        $perPage = in_array((int) $request->input('per_page'), [10, 25, 50, 100]) ? (int) $request->input('per_page') : 10;
        $installments = $query->orderBy('due_date', 'asc')->paginate($perPage)->withQueryString();

        // Summary stats
        $allInstallments = Installment::with('booking')->get();

        $stats = [
            'counts' => [
                'pendente' => $allInstallments->where('status', 'pendente')->count(),
                'pago' => $allInstallments->where('status', 'pago')->count(),
                'atrasado' => $allInstallments->where('status', 'atrasado')->count(),
                'falta_link' => $allInstallments->where('status', 'falta_link')->count(),
            ],
            'totals_by_currency' => [],
        ];

        // Group pending (non-pago) installments by booking currency and sum amounts
        $pendingInstallments = $allInstallments->where('status', '!=', 'pago');
        foreach ($pendingInstallments as $inst) {
            $currency = $inst->booking->currency ?? 'BRL';
            if (!isset($stats['totals_by_currency'][$currency])) {
                $stats['totals_by_currency'][$currency] = 0;
            }
            $stats['totals_by_currency'][$currency] += $inst->amount;
        }

        $tours = Tour::orderBy('name')->get();

        $filters = $request->only(['status', 'payment_method', 'search', 'date_from', 'date_to', 'tour_id']);

        return view('payments.index', compact('installments', 'stats', 'tours', 'filters'));
    }
}
