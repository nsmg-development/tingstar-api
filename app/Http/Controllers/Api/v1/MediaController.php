<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Repositories\Media\MediaRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MediaController extends Controller
{
    protected MediaRepositoryInterface $media;

    public function __construct(MediaRepositoryInterface $media)
    {
        $this->media = $media;
    }

    public function index(Request $request): Response
    {
        $result = $this->media->list($request);

        return $this->response($result);
    }

    public function show(Request $request, $media_id): Response
    {
        $result = $this->media->show($request, $media_id);

        return $this->response($result);
    }
}
