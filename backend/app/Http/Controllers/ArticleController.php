<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Http\Requests\ArticlesRequest;
use App\Http\Resources\ArticleResource;
use App\Repository\ArticleRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ArticleController extends Controller
{
    public function __construct(protected ArticleRepository $repository) {}

    public function index(ArticlesRequest $request): AnonymousResourceCollection
    {
        return ArticleResource::collection($this->repository->paginate(
            $request->validated(),
            (int) $request->input('per_page', 20),
        ));
    }

    public function show(Article $article): ArticleResource
    {
        return ArticleResource::make($article);
    }
}

