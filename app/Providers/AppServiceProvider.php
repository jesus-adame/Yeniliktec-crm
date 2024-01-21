<?php

namespace App\Providers;

use App\Services\PhpImapAdapter;
use App\Services\Contracts\ImapAdapter;
use App\Services\Contracts\WhatsAppAPI;
use App\Services\TwillioWhatsApp;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ImapAdapter::class, PhpImapAdapter::class);
        $this->app->singleton(WhatsAppAPI::class, TwillioWhatsApp::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
