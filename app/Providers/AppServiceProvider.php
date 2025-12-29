<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use SocialiteProviders\Manager\SocialiteWasCalled;
use SocialiteProviders\TikTok\TikTokExtendSocialite;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */

    public function boot(): void
    {
        if (app()->environment('production')) {
            URL::forceScheme('https');
        };

                // Registro do provider TikTok
        $this->app->events->listen(
            SocialiteWasCalled::class,
            TikTokExtendSocialite::class.'@handle'
        );
    }
}
