<?php

namespace SynergiTech\Creditsafe;

use Illuminate\Support\ServiceProvider;
use KJ\UIKit\UIKitServiceProvider;

class CreditsafeServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        if ($this->app->runningInConsole()) {
            UIKitServiceProvider::bootComponent($this, [
                'kj-config' => [
                    __DIR__.'/../config/creditsafe.php' => config_path('creditsafe.php'),
                ],
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/creditsafe.php', 'creditsafe');
    }
}