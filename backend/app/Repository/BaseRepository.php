<?php

namespace App\Repository;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueryBuilder\QueryBuilder;

abstract class BaseRepository
{
    protected array $allowedFilters = ['source', 'category', 'author', 'search', 'date_from', 'date_to'];
    protected array $allowedSorts = ['published_at', 'created_at'];
    protected string $defaultSort = '-created_at';

    public function __construct(protected Model $model) {}

    protected function normalizeFilters(array $filters): array
    {
        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $filters[$key] = implode(',', $value);
            }
        }

        return $filters;
    }

    public function paginate(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        return $this->buildQuery($filters)
            ->paginate($perPage)
            ->appends($filters);
    }

    protected function allows(string $filter): bool
    {
        return in_array($filter, $this->allowedFilters);
    }

    public function buildQuery(array $filters = []): QueryBuilder
    {
        $filters = $this->normalizeFilters($filters);

        $query = QueryBuilder::for($this->model::query())
            ->allowedFilters($this->allowedFilters)
            ->allowedSorts($this->allowedSorts)
            ->defaultSort($this->defaultSort);

        if ($this->allows('search') && ! empty($filters['search'])) {
            $query->whereRaw(
                "to_tsvector('english', title || ' ' || content) @@ plainto_tsquery('english', ?)",
                [$filters['search']],
            );
        }

        if ($this->allows('source') && ! empty($filters['source'])) {
            $sources = array_filter(array_map('trim', explode(',', $filters['source'])));
            if (! empty($sources)) {
                $query->whereIn('source', $sources);
            }
        }

        if ($this->allows('category') && ! empty($filters['category'])) {
            $categories = array_filter(array_map('trim', explode(',', $filters['category'])));
            if (! empty($categories)) {
                $query->whereIn('category', $categories);
            }
        }

        if ($this->allows('author') && ! empty($filters['author'])) {
            $query->where('author', $filters['author']);
        }

        if ($this->allows('date_from') && ! empty($filters['date_from'])) {
            $query->whereDate('published_at', '>=', $filters['date_from']);
        }

        if ($this->allows('date_to') && ! empty($filters['date_to'])) {
            $query->whereDate('published_at', '<=', $filters['date_to']);
        }

        return $query;
    }
}
