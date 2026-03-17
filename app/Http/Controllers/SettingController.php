<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\ActivityLog;
use App\Mail\PaymentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

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

        ActivityLog::log(__('messages.log_updated_settings'), 'Setting', null, [
            'groups' => ['payment_messages', 'pix', 'automation', 'smtp'],
        ]);

        return redirect()->route('settings.index')->with('success', __('messages.settings_saved'));
    }

    public function testEmail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        // Ensure SMTP settings from database are applied
        $smtpHost = Setting::get('smtp_host');
        if (!$smtpHost) {
            return redirect()->back()->with('error', __('messages.smtp_not_configured'));
        }

        Config::set('mail.default', 'smtp');
        Config::set('mail.mailers.smtp.host', $smtpHost);
        Config::set('mail.mailers.smtp.port', (int) Setting::get('smtp_port', '587'));
        Config::set('mail.mailers.smtp.username', Setting::get('smtp_username'));
        Config::set('mail.mailers.smtp.password', Setting::get('smtp_password'));
        Config::set('mail.mailers.smtp.encryption', Setting::get('smtp_encryption', 'tls'));
        Config::set('mail.from.name', Setting::get('smtp_from_name', 'MOJO Safaris & Tours'));
        Config::set('mail.from.address', Setting::get('smtp_from_email', 'noreply@mojosafaris.com'));

        // Purge the cached mailer so it picks up new config
        Mail::purge('smtp');

        try {
            Mail::to($request->test_email)->send(new PaymentNotification(
                __('messages.test_email_subject'),
                __('messages.test_email_body', ['datetime' => now()->format('d/m/Y H:i:s')])
            ));

            return redirect()->back()->with('success', __('messages.test_email_sent', ['email' => $request->test_email]));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('messages.test_email_failed', ['error' => $e->getMessage()]));
        }
    }
}
