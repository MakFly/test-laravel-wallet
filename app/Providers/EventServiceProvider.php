<?php

declare(strict_types=1);

namespace App\Providers;

use App\Listeners\CreateUserWallet;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            CreateUserWallet::class,
        ]
    ];
}
