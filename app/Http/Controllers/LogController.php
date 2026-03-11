<?php

namespace App\Http\Controllers;

use App\Models\EmailLog;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'emails');

        // Email Logs
        $emailLogQuery = EmailLog::with(['client', 'template']);

        if ($request->filled('date_from')) {
            $emailLogQuery->where('sent_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $emailLogQuery->where('sent_at', '<=', $request->date_to . ' 23:59:59');
        }

        if ($request->filled('trigger_type')) {
            $emailLogQuery->where('trigger_type', $request->trigger_type);
        }

        if ($request->filled('search') && $tab === 'emails') {
            $search = $request->search;
            $emailLogQuery->whereHas('client', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $emailLogs = $emailLogQuery->orderBy('sent_at', 'desc')->paginate(20, ['*'], 'email_page')->withQueryString();

        // Activity Logs
        $activityLogQuery = ActivityLog::query();

        if ($request->filled('date_from')) {
            $activityLogQuery->where('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $activityLogQuery->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        if ($request->filled('entity_type')) {
            $activityLogQuery->where('entity_type', $request->entity_type);
        }

        if ($request->filled('search') && $tab === 'activity') {
            $search = $request->search;
            $activityLogQuery->where('action', 'like', "%{$search}%");
        }

        $activityLogs = $activityLogQuery->orderBy('created_at', 'desc')->paginate(20, ['*'], 'activity_page')->withQueryString();

        // Entity types for filter dropdown
        $entityTypes = ActivityLog::distinct()->pluck('entity_type')->filter()->values();

        return view('logs.index', compact('tab', 'emailLogs', 'activityLogs', 'entityTypes'));
    }
}
