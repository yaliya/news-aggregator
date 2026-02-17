<?php

namespace App\Services\NewsAggregator\Sources;

use GuzzleHttp\ClientInterface;
use Illuminate\Support\Collection;
use App\Services\NewsAggregator\Contracts\NewsSourceInterface;

class NewsApiSource implements NewsSourceInterface
{
    public function __construct(
        private readonly ClientInterface $http,
        private readonly string $apiKey,
        private readonly string $endpoint,
    ) {
    }

    public function getSourceName(): string
    {
        return 'newsapi';
    }

    public function fetchArticles(array $params = []): Collection
    {
        $query = array_merge(['pageSize' => 50], $params);

        $response = $this->http->request('GET', $this->endpoint, [
            'query' => $query,
            'headers' => [
                'X-Api-Key' => $this->apiKey,
            ],
            'timeout' => 10,
        ]);

        $payload = json_decode((string) $response->getBody(), true, flags: JSON_THROW_ON_ERROR);

        return Collection::make($payload['articles'] ?? []);
    }

    public function transformArticle(array $data): array
    {
        return [
            'source_id' => $data['url'] ?? null, // NewsAPI does not provide a stable ID, URL is closest
            'title' => $data['title'] ?? null,
            'content' => $data['content'] ?? null,
            'description' => $data['description'] ?? null,
            'author' => $data['author'] ?? null,
            'category' => $data['category'] ?? null,
            'url' => $data['url'] ?? null,
            'image_url' => $data['urlToImage'] ?? null,
            'published_at' => $data['publishedAt'] ?? null,
        ];
    }
}

