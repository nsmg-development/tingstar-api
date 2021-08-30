<?php

namespace App\Repositories\Article;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface ArticleUserFavoriteRepositoryInterface
{
    public function list(Request $request): Collection;
    public function store(Request $request): Collection;
    public function destroy(Request $request, int $article_user_favorite_id): Collection;
}
