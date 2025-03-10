<?php

namespace App\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use App\Models\SmtpSetting;

class SmtpServiceProvider extends ServiceProvider
{
    public function boot()
    {
        try {
            $smtpSettings = SmtpSetting::first();

            if ($smtpSettings) {
                Config::set('mail.mailers.smtp.host', $smtpSettings->host);
                Config::set('mail.mailers.smtp.port', (int)$smtpSettings->port);
                Config::set('mail.mailers.smtp.username', $smtpSettings->username);
                Config::set('mail.mailers.smtp.password', $smtpSettings->password);
                Config::set('mail.mailers.smtp.encryption', $smtpSettings->encryption);
                Config::set('mail.from.address', $smtpSettings->from_address);
            }
        } catch (\Throwable $e) {
            // Biarkan konfigurasi .env tetap digunakan jika database tidak tersedia
        }
    }
}