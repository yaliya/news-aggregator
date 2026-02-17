<?php

namespace App\Repository;

use App\Models\Article;

class UserFeedRepository extends BaseRepository
{
    protected array $allowedFilters = ['source', 'category', 'author'];
    protected string $defaultSort = '-published_at';

    public function __construct()
    {
        parent::__construct(new Article);
    }
}
