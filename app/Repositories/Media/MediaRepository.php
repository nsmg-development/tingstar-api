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
        $medias = $this->media->get();

        if (!count($medias) > 0) {
            return collect([
                'statusCode' => 404,
                'message' => '매체 정보가 존재하지 않습니다.'
            ]);
        }

        return $medias;
    }

    public function show(Request $request, int $media_id): Collection
    {
        $media = $this->media->where('id', $media_id)
            ->with(['channels', 'keywords'])
            ->first();

        if (!$media) {
            return collect([
                'statusCode' => 404,
                'message' => '매체 정보가 존재하지 않습니다.'
            ]);
        }

        return collect($media);
    }
}
