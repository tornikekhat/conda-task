<?php

namespace App\Providers;

use App\Repositories\ContactRepository;
use App\Repositories\Interfaces\ContactRepositoryInterface;
use App\Services\ContactService;
use App\Services\Interfaces\ContactServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ContactServiceInterface::class, ContactService::class);
        $this->app->bind(ContactRepositoryInterface::class, ContactRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
