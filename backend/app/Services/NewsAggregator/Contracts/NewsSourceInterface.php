<?php

namespace App\Services\NewsAggregator\Contracts;

use Illuminate\Support\Collection;

interface NewsSourceInterface
{
    /**
     * Fetch raw articles from the external API.
     *
     * @param  array<string, mixed>  $params
     * @return \Illuminate\Support\Collection<int, array<string, mixed>>
     */
    public function fetchArticles(array $params = []): Collection;

    /**
     * Machine-friendly source name, e.g. "newsapi", "guardian", "nytimes".
     */
    public function getSourceName(): string;

    /**
     * Transform a raw API article payload into a normalized structure
     * compatible with the local Article model/database schema.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function transformArticle(array $data): array;
}

