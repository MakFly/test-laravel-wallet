<?php

declare(strict_types=1);

use App\Exceptions\ApiException;
use App\Http\Middleware\ForceAcceptJson;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(append: [
            ForceAcceptJson::class,
        ]);
    })
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('app:execute-recurring-transfers')->dailyAt('02:00');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->reportable(function (Throwable $e) {
            if ($e instanceof ApiException) {
                return ! $e->silenced;
            }

            return true;
        });
    })->create();
