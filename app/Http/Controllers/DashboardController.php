<?php

namespace App\Http\Controllers;

use App\Models\Installment;
use App\Models\Booking;
use App\Models\ActivityLog;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Update resolved statuses for non-paid installments
        $allInstallments = Installment::where('status', '!=', 'pago')->with('booking')->get();
        foreach ($allInstallments as $installment) {
            $resolved = $installment->resolveStatus();
            if ($installment->status !== $resolved) {
                $installment->status = $resolved;
                $installment->save();
            }
        }

        // Status counts
        $statusCounts = [
            'vence_breve' => Installment::where('status', '!=', 'pago')
                ->whereBetween('due_date', [$today->copy()->addDay(), $today->copy()->addDays(7)])
                ->count(),
            'vence_hoje' => Installment::where('status', '!=', 'pago')
                ->whereDate('due_date', $today)
                ->count(),
            'atrasado' => Installment::where('status', 'atrasado')->count(),
            'pago' => Installment::where('status', 'pago')->count(),
            'falta_link' => Installment::where('status', 'falta_link')->count(),
        ];

        // Totals by currency for pending (non-paid) installments
        $currencyTotals = Installment::where('installments.status', '!=', 'pago')
            ->join('bookings', 'installments.booking_id', '=', 'bookings.id')
            ->selectRaw('bookings.currency, SUM(installments.amount) as total, COUNT(*) as count')
            ->groupBy('bookings.currency')
            ->get()
            ->keyBy('currency');

        // Recent activity
        $recentActivity = ActivityLog::orderBy('created_at', 'desc')->limit(10)->get();

        // Upcoming due installments (next 7 days, not paid)
        $upcomingInstallments = Installment::where('status', '!=', 'pago')
            ->whereBetween('due_date', [$today, $today->copy()->addDays(7)])
            ->with('booking.client', 'booking.tour')
            ->orderBy('due_date')
            ->limit(10)
            ->get();

        // Overdue installments
        $overdueInstallments = Installment::where('status', 'atrasado')
            ->with('booking.client', 'booking.tour')
            ->orderBy('due_date')
            ->limit(10)
            ->get();

        // Booking stats
        $bookingStats = [
            'total' => Booking::count(),
            'pendente' => Booking::where('status', 'pendente')->count(),
            'confirmado' => Booking::where('status', 'confirmado')->count(),
            'concluido' => Booking::where('status', 'concluido')->count(),
        ];

        return view('dashboard.index', compact(
            'statusCounts',
            'currencyTotals',
            'recentActivity',
            'upcomingInstallments',
            'overdueInstallments',
            'bookingStats'
        ));
    }
}
