<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Repositories\Platform\PlatformRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PlatformController extends Controller
{
    protected PlatformRepositoryInterface $platform;

    public function __construct(PlatformRepositoryInterface $platform)
    {
        $this->platform = $platform;
    }

    /**
     * 플랫폼 리스트 with 플랫폼 로그인 계정 정보
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request): Response
    {
        $result = $this->platform->list($request);

        return $this->response($result);
    }

    /**
     * 플랫폼 상세보기 with 플랫폼 로그인 계정 정보
     *
     * @param Request $request
     * @param $platform_id
     *
     * @return Response
     */
    public function show(Request $request, $platform_id): Response
    {
        $result = $this->platform->show($request, $platform_id);

        return $this->response($result);
    }
}
