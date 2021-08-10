<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Repositories\Platform\PlatformAccountRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PlatformAccountController extends Controller
{
    protected PlatformAccountRepositoryInterface $platformAccount;

    public function __construct(PlatformAccountRepositoryInterface $platformAccount)
    {
        $this->platformAccount = $platformAccount;
    }

    /**
     * 플랫폼 로그인 계정 등록
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request): Response
    {
        $result = $this->platformAccount->store($request);

        return $this->response($result);
    }

    /**
     * 플랫폼 로그인 계정 업데이트
     *
     * @param Request $request
     * @param $platform_account_id
     *
     * @return Response
     */
    public function update(Request $request, $platform_account_id): Response
    {
        $result = $this->platformAccount->update($request, $platform_account_id);

        return $this->response($result);
    }
}
