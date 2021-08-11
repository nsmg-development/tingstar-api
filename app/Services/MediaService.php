<?php

namespace App\Services;

use App\Models\Media;

class MediaService
{
    protected Media $media;

    public function __construct(Media $media)
    {
        $this->media = $media;
    }
}
