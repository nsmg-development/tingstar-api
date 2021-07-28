<?php

namespace App\Repositories\User;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    public function store(Request $request): Collection;
}
