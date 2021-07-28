<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponseTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Collection;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use ApiResponseTrait;

    /**
     * API 결과 코드 생성 후 전달
     *
     * @param Collection $result
     * @return \Illuminate\Http\Response
     */
    protected function response(Collection $result): Response
    {
        return response($this->makeResponse($result), $result->get('statusCode') ?? 500);
    }
}
