<?php

namespace App\Http\Controllers;

use App\Models\Installment;
use App\Models\Booking;
use App\Models\Tour;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $isViewer = auth()->user()->isViewer();
        $userId = auth()->id();

        // Update resolved statuses for non-paid installments
        $allInstallments = Installment::where('status', '!=', 'pago')->with('booking')->get();
        foreach ($allInstallments as $installment) {
            $resolved = $installment->resolveStatus();
            if ($installment->status !== $resolved) {
                $installment->status = $resolved;
                $installment->save();
            }
        }

        // Helper: scope installment queries for viewer
        $scopeInstallment = function ($query) use ($isViewer, $userId) {
            if ($isViewer) {
                $query->whereHas('booking', fn($q) => $q->where('created_by', $userId));
            }
            return $query;
        };

        // Helper: scope booking queries for viewer
        $scopeBooking = function ($query) use ($isViewer, $userId) {
            if ($isViewer) {
                $query->where('created_by', $userId);
            }
            return $query;
        };

        // Viewer gets a simplified dashboard with only their data
        if ($isViewer) {
            // Booking stats (viewer's own)
            $bookingStats = [
                'total' => Booking::where('created_by', $userId)->count(),
                'pendente' => Booking::where('created_by', $userId)->where('status', 'pendente')->count(),
                'confirmado' => Booking::where('created_by', $userId)->where('status', 'confirmado')->count(),
                'concluido' => Booking::where('created_by', $userId)->where('status', 'concluido')->count(),
            ];

            // Payment status (viewer's own installments)
            $paymentStatusChart = [
                'Pendente' => $scopeInstallment(Installment::where('status', 'pendente'))->count(),
                'Pago' => $scopeInstallment(Installment::where('status', 'pago'))->count(),
                'Atrasado' => $scopeInstallment(Installment::where('status', 'atrasado'))->count(),
                'Falta Link' => $scopeInstallment(Installment::where('status', 'falta_link'))->count(),
            ];

            // Bookings per month (viewer's own)
            $sixMonthsAgo = $today->copy()->subMonths(5)->startOfMonth();
            $monthlyBookings = Booking::where('created_by', $userId)
                ->where('created_at', '>=', $sixMonthsAgo)
                ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total")
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            $bookingsLabels = [];
            $bookingsData = [];
            for ($i = 5; $i >= 0; $i--) {
                $m = $today->copy()->subMonths($i);
                $key = $m->format('Y-m');
                $bookingsLabels[] = $m->translatedFormat('M/Y');
                $found = $monthlyBookings->first(fn($r) => $r->month === $key);
                $bookingsData[] = $found ? $found->total : 0;
            }

            // Tours by type (all tours are visible to viewers)
            $toursByType = Tour::selectRaw("type, COUNT(*) as total")
                ->groupBy('type')
                ->pluck('total', 'type')
                ->toArray();

            $typeLabels = [
                'grupo' => 'Grupo',
                'privado' => 'Privado',
                'agencia' => 'Agencia',
                'influencer' => 'Influencer',
            ];
            $tourTypeChart = [];
            foreach ($toursByType as $type => $count) {
                $tourTypeChart[$typeLabels[$type] ?? ucfirst($type)] = $count;
            }

            return view('dashboard.viewer', compact(
                'bookingStats',
                'paymentStatusChart',
                'bookingsLabels',
                'bookingsData',
                'tourTypeChart'
            ));
        }

        // === ADMIN / MANAGER DASHBOARD ===

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

        // === CHART DATA ===

        // 1) Monthly Revenue — paid installments over last 6 months
        $sixMonthsAgo = $today->copy()->subMonths(5)->startOfMonth();
        $monthlyRevenue = Installment::where('installments.status', 'pago')
            ->where('installments.paid_at', '>=', $sixMonthsAgo)
            ->join('bookings', 'installments.booking_id', '=', 'bookings.id')
            ->selectRaw("DATE_FORMAT(installments.paid_at, '%Y-%m') as month, bookings.currency, SUM(installments.amount) as total")
            ->groupBy('month', 'currency')
            ->orderBy('month')
            ->get();

        // Build labels (last 6 months)
        $revenueLabels = [];
        $revenueData = ['BRL' => [], 'USD' => [], 'EUR' => []];
        for ($i = 5; $i >= 0; $i--) {
            $m = $today->copy()->subMonths($i);
            $key = $m->format('Y-m');
            $revenueLabels[] = $m->translatedFormat('M/Y');
            foreach (['BRL', 'USD', 'EUR'] as $cur) {
                $found = $monthlyRevenue->first(fn($r) => $r->month === $key && $r->currency === $cur);
                $revenueData[$cur][] = $found ? round($found->total, 2) : 0;
            }
        }
        // Remove currencies with all zeros
        $revenueData = array_filter($revenueData, fn($vals) => array_sum($vals) > 0);

        // 2) Payment Status Distribution — all installments
        $paymentStatusChart = [
            'Pendente' => Installment::where('status', 'pendente')->count(),
            'Pago' => Installment::where('status', 'pago')->count(),
            'Atrasado' => Installment::where('status', 'atrasado')->count(),
            'Falta Link' => Installment::where('status', 'falta_link')->count(),
        ];

        // 3) Bookings per Month — last 6 months
        $monthlyBookings = Booking::where('created_at', '>=', $sixMonthsAgo)
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $bookingsLabels = [];
        $bookingsData = [];
        for ($i = 5; $i >= 0; $i--) {
            $m = $today->copy()->subMonths($i);
            $key = $m->format('Y-m');
            $bookingsLabels[] = $m->translatedFormat('M/Y');
            $found = $monthlyBookings->first(fn($r) => $r->month === $key);
            $bookingsData[] = $found ? $found->total : 0;
        }

        // 4) Tours by Type
        $toursByType = Tour::selectRaw("type, COUNT(*) as total")
            ->groupBy('type')
            ->pluck('total', 'type')
            ->toArray();

        $typeLabels = [
            'grupo' => 'Grupo',
            'privado' => 'Privado',
            'agencia' => 'Agencia',
            'influencer' => 'Influencer',
        ];
        $tourTypeChart = [];
        foreach ($toursByType as $type => $count) {
            $tourTypeChart[$typeLabels[$type] ?? ucfirst($type)] = $count;
        }

        return view('dashboard.index', compact(
            'statusCounts',
            'currencyTotals',
            'recentActivity',
            'upcomingInstallments',
            'overdueInstallments',
            'bookingStats',
            'revenueLabels',
            'revenueData',
            'paymentStatusChart',
            'bookingsLabels',
            'bookingsData',
            'tourTypeChart'
        ));
    }
}
