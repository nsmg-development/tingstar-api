<?php

namespace App\Repositories\Media;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface MediaRepositoryInterface
{
    public function list(Request $request): Collection;
    public function show(Request $request, int $media_id): Collection;
}
