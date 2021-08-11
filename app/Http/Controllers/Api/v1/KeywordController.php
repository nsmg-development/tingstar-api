<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Repositories\Keyword\KeywordRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class KeywordController extends Controller
{
    protected KeywordRepositoryInterface $keyword;

    public function __construct(KeywordRepositoryInterface $keyword)
    {
        $this->keyword = $keyword;
    }

    /**
     * 매체 키워드 정보 등록
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request): Response
    {
        $result = $this->keyword->store($request);

        return $this->response($result);
    }

    /**
     * 매체 키워드 정보 업데이트
     *
     * @param Request $request
     * @param $keyword_id
     *
     * @return Response
     */
    public function update(Request $request, $keyword_id): Response
    {
        $result = $this->keyword->update($request, $keyword_id);

        return $this->response($result);
    }
}
