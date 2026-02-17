<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserFeedRequest;
use App\Http\Resources\ArticleResource;
use App\Repository\UserFeedRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserFeedController extends Controller
{
    public function __construct(protected UserFeedRepository $repository) {}

    public function index(UserFeedRequest $request): AnonymousResourceCollection
    {
        return ArticleResource::collection($this->repository->paginate(
            $request->user('api')->preferences ?? [],
            (int) $request->input('per_page', 20),
        ));
    }
}
