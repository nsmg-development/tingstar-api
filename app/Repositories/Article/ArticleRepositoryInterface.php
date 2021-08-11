<?php

namespace App\Repositories\Article;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface ArticleRepositoryInterface
{
    public function list(Request $request): Collection;
    public function show(Request $request, int $article_id): Collection;
    public function setState(Request $request, int $article_id): Collection;
    public function setMultipleState(Request $request): Collection;
}

