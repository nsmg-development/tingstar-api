<?php

namespace App\Repositories\Article;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface ArticleUserFavoriteRepositoryInterface
{
    public function list(Request $request): Collection;
    public function setFavorite(Request $request): Collection;
}
