<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;
use App\Model\Setting;
use App\Model\SendingServer;
use App\Model\SendingServerSmtp;
use App\Model\SendingServerSendmail;

class MailerServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('xmailer', function ($app) {
            $driver = Setting::get('mailer.driver') ?? config('mail.driver');

            switch ($driver) {
                case SendingServer::TYPE_SMTP:
                    $mailer = SendingServerSmtp::instantiateFromSettings([
                        'host' => Setting::get('mailer.host') ?? config('mail.host'),
                        'smtp_port' => Setting::get('mailer.port') ?? config('mail.port'),
                        'smtp_protocol' => Setting::get('mailer.encryption') ?? config('mail.encryption'),
                        'smtp_username' => Setting::get('mailer.username') ?? config('mail.username'),
                        'smtp_password' => Setting::get('mailer.password') ?? config('mail.password'),
                        'from_name' => Setting::get('mailer.from.name') ?? config('mail.from.name'),
                        'from_address' => Setting::get('mailer.from.address') ?? config('mail.from.address'),
                    ]);

                    break;

                case SendingServer::TYPE_SENDMAIL:
                    $mailer = SendingServerSendmail::instantiateFromSettings([
                        'sendmail_path' => Setting::get('mailer.sendmail_path') ?? config('mail.sendmail'),
                        'from_name' => Setting::get('mailer.from.name') ?? config('mail.from.name'),
                        'from_address' => Setting::get('mailer.from.address') ?? config('mail.from.address'),
                    ]);
                    break;
                default:
                    throw new \Exception("Mail driver '{$driver}' not found by Acelle", 1);
                    break;
            }

            return $mailer;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['xmailer'];
    }
}
