<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class DynamicMailServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Only configure if the settings table exists (prevents errors during migration)
        try {
            if (!Schema::hasTable('settings')) {
                return;
            }

            $smtpHost = Setting::get('smtp_host');
            if (!$smtpHost) {
                return;
            }

            Config::set('mail.default', 'smtp');
            Config::set('mail.mailers.smtp.host', $smtpHost);
            Config::set('mail.mailers.smtp.port', (int) Setting::get('smtp_port', '587'));
            Config::set('mail.mailers.smtp.username', Setting::get('smtp_username'));
            Config::set('mail.mailers.smtp.password', Setting::get('smtp_password'));
            Config::set('mail.mailers.smtp.encryption', Setting::get('smtp_encryption', 'tls'));
            Config::set('mail.from.name', Setting::get('smtp_from_name', 'MOJO Safaris & Tours'));
            Config::set('mail.from.address', Setting::get('smtp_from_email', 'noreply@mojosafaris.com'));
        } catch (\Exception $e) {
            // Silently fail if database isn't ready
        }
    }
}
