<?php

namespace App\Services\NewsAggregator;

use App\Models\Article;
use App\Services\NewsAggregator\Contracts\NewsSourceInterface;
use Illuminate\Support\Collection;

class NewsAggregatorService
{
    private array $sources = [];

    public function __construct(
        iterable $sources,
        private readonly ArticleTransformer $transformer,
    ) {
        foreach ($sources as $source) {
            $this->sources[$source->getSourceName()] = $source;
        }
    }

    public function fetchAndStore(array $params = []): array
    {
        $results = [];

        foreach ($this->sources as $source) {
            $results[$source->getSourceName()] = $this->fetchFromSource($source, $params);
        }

        return $results;
    }

    public function fetchBySourceName(string $sourceName, array $params = []): int
    {
        $source = $this->sources[$sourceName] ?? null;

        if (! $source) {
            throw new \InvalidArgumentException("Unknown news source: {$sourceName}");
        }

        return $this->fetchFromSource($source, $params);
    }

    public function fetchFromSource(NewsSourceInterface $source, array $params = []): int
    {
        $articles = $source->fetchArticles($params);

        if ($articles->isEmpty()) {
            return 0;
        }

        $normalized = $this->normalizeArticles($source, $articles);

        Article::upsert(
            $normalized,
            ['source', 'source_id'],
            ['title', 'content', 'description', 'author', 'category', 'url', 'image_url', 'published_at', 'fetched_at', 'updated_at']
        );

        return count($normalized);
    }

    private function normalizeArticles(NewsSourceInterface $source, Collection $articles): array
    {
        $sourceName = $source->getSourceName();

        return $articles
            ->map(fn (array $article) => $source->transformArticle($article))
            ->map(fn (array $article) => $this->transformer->transform($sourceName, $article))
            ->all();
    }
}
