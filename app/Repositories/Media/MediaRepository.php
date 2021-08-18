<?php

namespace App\Repositories\Media;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class MediaRepository implements MediaRepositoryInterface
{
    protected Media $media;

    public function __construct(Media $media)
    {
        $this->media = $media;
    }

    public function list(Request $request): Collection
    {
        return $this->media->get();
    }

    public function show(Request $request, int $media_id): Collection
    {
        return $this->media->where('id', $media_id)
            ->with(['channels', 'keywords'])
            ->get();
    }
}
