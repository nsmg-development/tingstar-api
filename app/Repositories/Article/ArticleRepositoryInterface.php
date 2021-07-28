<?php

namespace App\Repositories\Article;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface ArticleRepositoryInterface
{
    public function get(Request $request): Collection;
}

