<?php

namespace App\Repositories\Platform;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface PlatformRepositoryInterface
{
    public function list(Request $request): Collection;
    public function show(Request $request, int $article_id): Collection;
}

