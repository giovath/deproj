<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use SocialiteProviders\Manager\SocialiteWasCalled;
use SocialiteProviders\TikTok\TikTokExtendSocialite;
use App\Services\Games\Contracts\GameProviderInterface;
use App\Services\Games\Providers\GamePixProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            GameProviderInterface::class,
            GamePixProvider::class
        );
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
            TikTokExtendSocialite::class . '@handle'
        );
    }
}
