<?php

namespace App\Repositories\Keyword;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface KeywordRepositoryInterface
{
    public function store(Request $request): Collection;
    public function update(Request $request, int $keyword_id): Collection;
}
