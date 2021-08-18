<?php

namespace App\Repositories\Article;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface ArticleDetailRepositoryInterface
{
    public function toggleBehavior(Request $request, int $article_id, string $behavior_type): Collection;
}
