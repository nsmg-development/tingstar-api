<?php

namespace App\Repositories\Article;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface ArticleRepositoryInterface
{
    public function getList(Request $request): Collection;
    public function getDetail(Request $request, int $article_id): Collection;
}

