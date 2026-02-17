<?php

namespace App\Services\NewsAggregator\Sources;

use GuzzleHttp\ClientInterface;
use Illuminate\Support\Collection;
use App\Services\NewsAggregator\Contracts\NewsSourceInterface;

class GuardianSource implements NewsSourceInterface
{
    public function __construct(
        private readonly ClientInterface $http,
        private readonly string $apiKey,
        private readonly string $endpoint,
    ) {
    }

    public function getSourceName(): string
    {
        return 'guardian';
    }

    public function fetchArticles(array $params = []): Collection
    {
        $query = array_merge([
            'page-size' => 50,
            'show-fields' => 'headline,trailText,bodyText,thumbnail,byline',
        ], $params);

        $response = $this->http->request('GET', $this->endpoint, [
            'query' => $query,
            'headers' => [
                'api-key' => $this->apiKey,
            ],
            'timeout' => 10,
        ]);

        $payload = json_decode((string) $response->getBody(), true, flags: JSON_THROW_ON_ERROR);

        return Collection::make($payload['response']['results'] ?? []);
    }

    public function transformArticle(array $data): array
    {
        $fields = $data['fields'] ?? [];

        return [
            'source_id' => $data['id'] ?? null,
            'title' => $fields['headline'] ?? $data['webTitle'] ?? null,
            'content' => $fields['bodyText'] ?? null,
            'description' => $fields['trailText'] ?? null,
            'author' => $fields['byline'] ?? null,
            'category' => $data['sectionName'] ?? null,
            'url' => $data['webUrl'] ?? null,
            'image_url' => $fields['thumbnail'] ?? null,
            'published_at' => $data['webPublicationDate'] ?? null,
        ];
    }
}

