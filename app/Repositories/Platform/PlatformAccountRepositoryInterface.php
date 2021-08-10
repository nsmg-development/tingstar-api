<?php

namespace App\Repositories\Platform;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface PlatformAccountRepositoryInterface
{
    public function list(Request $request): Collection;
    public function store(Request $request): Collection;
    public function update(Request $request, $platform_account_id): Collection;
}

