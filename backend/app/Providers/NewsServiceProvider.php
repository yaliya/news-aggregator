<?php

namespace App\Providers;

use App\Services\NewsAggregator\ArticleTransformer;
use App\Services\NewsAggregator\NewsAggregatorService;
use App\Services\NewsAggregator\Sources\GuardianSource;
use App\Services\NewsAggregator\Sources\NewsApiSource;
use App\Services\NewsAggregator\Sources\NYTimesSource;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class NewsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(NewsAggregatorService::class, function ($app) {
            $config = $app['config']['news.sources'];

            $client = new Client([
                'timeout' => 10,
            ]);

            $sources = [];

            if (($config['newsapi']['enabled'] ?? false) && ! empty($config['newsapi']['api_key'])) {
                $sources[] = new NewsApiSource(
                    $client,
                    $config['newsapi']['api_key'],
                    $config['newsapi']['endpoint'],
                );
            }

            if (($config['guardian']['enabled'] ?? false) && ! empty($config['guardian']['api_key'])) {
                $sources[] = new GuardianSource(
                    $client,
                    $config['guardian']['api_key'],
                    $config['guardian']['endpoint'],
                );
            }

            if (($config['nytimes']['enabled'] ?? false) && ! empty($config['nytimes']['api_key'])) {
                $sources[] = new NYTimesSource(
                    $client,
                    $config['nytimes']['api_key'],
                    $config['nytimes']['endpoint'],
                );
            }

            return new NewsAggregatorService(
                $sources,
                $app->make(ArticleTransformer::class),
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

