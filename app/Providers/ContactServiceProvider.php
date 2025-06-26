<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Contact\Contracts\ContactServiceInterface;
use App\Domain\Contact\Services\ContactService;

class ContactServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        $this->app->bind(ContactServiceInterface::class, ContactService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
