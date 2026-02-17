<?php

namespace App\Services\NewsAggregator\Sources;

use GuzzleHttp\ClientInterface;
use Illuminate\Support\Collection;
use App\Services\NewsAggregator\Contracts\NewsSourceInterface;

class NYTimesSource implements NewsSourceInterface
{
    public function __construct(
        private readonly ClientInterface $http,
        private readonly string $apiKey,
        private readonly string $endpoint,
    ) {
    }

    public function getSourceName(): string
    {
        return 'nytimes';
    }

    public function fetchArticles(array $params = []): Collection
    {
        $query = array_merge([
            'sort' => 'newest',
            'page' => 0,
        ], $params);

        $response = $this->http->request('GET', $this->endpoint, [
            'query' => $query,
            'headers' => [
                'api-key' => $this->apiKey,
            ],
            'timeout' => 10,
        ]);

        $payload = json_decode((string) $response->getBody(), true, flags: JSON_THROW_ON_ERROR);

        return Collection::make($payload['response']['docs'] ?? []);
    }

    public function transformArticle(array $data): array
    {
        $headline = $data['headline'] ?? [];
        $multimedia = $data['multimedia'] ?? [];

        $image = Collection::make($multimedia)->firstWhere('subtype', 'xlarge')
            ?? ($multimedia[0] ?? null);

        return [
            'source_id' => $data['_id'] ?? null,
            'title' => $headline['main'] ?? null,
            'content' => $data['lead_paragraph'] ?? null,
            'description' => $data['abstract'] ?? null,
            'author' => $data['byline']['original'] ?? null,
            'category' => $data['section_name'] ?? null,
            'url' => $data['web_url'] ?? null,
            'image_url' => isset($image['url']) ? 'https://www.nytimes.com/' . ltrim($image['url'], '/') : null,
            'published_at' => $data['pub_date'] ?? null,
        ];
    }
}

