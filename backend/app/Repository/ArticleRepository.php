<?php

namespace App\Repository;

use App\Models\Article;

class ArticleRepository extends BaseRepository
{
    protected string $defaultSort = '-published_at';

    public function __construct()
    {
        parent::__construct(new Article);
    }
}
