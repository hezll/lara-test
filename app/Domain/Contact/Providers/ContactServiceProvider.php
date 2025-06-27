<?php

namespace App\Domain\Contact\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Contact\Contracts\ContactServiceInterface;
use App\Domain\Contact\Services\ContactService;

class ContactServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ContactServiceInterface::class, ContactService::class);
    }
}
