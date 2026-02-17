<?php

namespace App\Services\NewsAggregator;

use Carbon\CarbonImmutable;

class ArticleTransformer
{
    /**
     * Normalize a raw article payload to match the articles table schema.
     *
     * @param  string  $source
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function transform(string $source, array $data): array
    {
        // These keys are intentionally generic; individual source implementations
        // should map their payloads into this shape before persistence.
        return [
            'source' => $source,
            'source_id' => (string) ($data['source_id'] ?? $data['id'] ?? ''),
            'title' => (string) ($data['title'] ?? ''),
            'content' => $data['content'] ?? null,
            'description' => $data['description'] ?? null,
            'author' => $data['author'] ?? null,
            'category' => $data['category'] ?? null,
            'url' => $data['url'] ?? '',
            'image_url' => $data['image_url'] ?? null,
            'published_at' => $this->parseDateTime($data['published_at'] ?? null),
            'fetched_at' => CarbonImmutable::now(),
        ];
    }

    /**
     * @param  mixed  $value
     */
    private function parseDateTime(mixed $value): ?CarbonImmutable
    {
        if ($value === null || $value === '') {
            return null;
        }

        try {
            return CarbonImmutable::parse($value);
        } catch (\Throwable) {
            return null;
        }
    }
}

