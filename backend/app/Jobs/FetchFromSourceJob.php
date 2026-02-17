<?php

namespace App\Jobs;

use App\Services\NewsAggregator\NewsAggregatorService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchFromSourceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * Create a new job instance.
     *
     * @param  array<string, mixed>  $params
     */
    public function __construct(
        public string $sourceName,
        public array $params = [],
    ) {
        //
    }

    /**
     * Determine the time at which the job should timeout.
     */
    public function backoff(): array
    {
        return [60, 120, 300];
    }

    /**
     * Execute the job.
     */
    public function handle(NewsAggregatorService $aggregator): void
    {
        $aggregator->fetchBySourceName($this->sourceName, $this->params);
    }
}

