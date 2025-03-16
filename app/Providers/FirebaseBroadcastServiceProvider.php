<?php

namespace App\Providers;

use App\Services\FirebaseBroadcaster;
use Illuminate\Broadcasting\BroadcastManager;
use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Contract\Messaging;

class FirebaseBroadcastServiceProvider extends ServiceProvider
{
    /**
     * Register services.   
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->make(BroadcastManager::class)->extend('custom', function ($app, $config) {
            return new FirebaseBroadcaster($app->make(Messaging::class));
        });
    }
}
