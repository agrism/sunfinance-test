<?php

namespace App\Providers;

use App\Jobs\SendNotificationJob;
use App\Repositories\ClientRepositoryInterface;
use App\Repositories\Eloquent\ClientRepository;
use App\Repositories\Eloquent\NotificationRepository;
use App\Repositories\NotificationRepositoryInterface;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(SendNotificationJob::class, fn($job) => $job->handle());
        $this->app->bind(ClientRepositoryInterface::class, ClientRepository::class);
        $this->app->bind(NotificationRepositoryInterface::class, NotificationRepository::class);
    }
}
