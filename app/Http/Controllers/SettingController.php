<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->groupBy('group');

        // Build a keyed array for easy access in the view
        $allSettings = Setting::all()->pluck('value', 'key')->toArray();

        return view('settings.index', compact('settings', 'allSettings'));
    }

    public function update(Request $request)
    {
        // Payment messages
        Setting::set('msg_link', $request->input('msg_link'), 'payment_messages');
        Setting::set('msg_pix', $request->input('msg_pix'), 'payment_messages');
        Setting::set('msg_wise', $request->input('msg_wise'), 'payment_messages');

        // PIX
        Setting::set('pix_instructions', $request->input('pix_instructions'), 'pix');

        // Automation
        $automationKeys = [
            'auto_7days_before',
            'auto_3days_before',
            'auto_due_date',
            'auto_1day_after',
            'auto_7days_after',
        ];
        foreach ($automationKeys as $key) {
            Setting::set($key, $request->has($key) ? '1' : '0', 'automation');
        }

        // SMTP
        Setting::set('smtp_host', $request->input('smtp_host'), 'smtp');
        Setting::set('smtp_port', $request->input('smtp_port'), 'smtp');
        Setting::set('smtp_username', $request->input('smtp_username'), 'smtp');
        Setting::set('smtp_password', $request->input('smtp_password'), 'smtp');
        Setting::set('smtp_encryption', $request->input('smtp_encryption'), 'smtp');
        Setting::set('smtp_from_name', $request->input('smtp_from_name'), 'smtp');
        Setting::set('smtp_from_email', $request->input('smtp_from_email'), 'smtp');

        ActivityLog::log('atualizou configurações', 'Setting', null, [
            'groups' => ['payment_messages', 'pix', 'automation', 'smtp'],
        ]);

        return redirect()->route('settings.index')->with('success', 'Configurações salvas com sucesso.');
    }
}
