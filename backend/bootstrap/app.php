<?php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        App\Providers\NewsServiceProvider::class,
    ])
    ->withSchedule(function (Schedule $schedule): void {
        $schedule->job(new App\Jobs\FetchArticlesJob())
            ->everyMinute()
            ->withoutOverlapping();
    })
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'ensure.preferences' => App\Http\Middleware\EnsureUserHasPreferences::class,
            'log.api' => App\Http\Middleware\LogApiRequests::class,
            'validate.news.source' => App\Http\Middleware\ValidateNewsSource::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
