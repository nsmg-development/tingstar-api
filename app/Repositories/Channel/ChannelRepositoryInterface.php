<?php

namespace App\Repositories\Channel;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface ChannelRepositoryInterface
{
    public function store(Request $request): Collection;
    public function update(Request $request, int $channel_id): Collection;
}
