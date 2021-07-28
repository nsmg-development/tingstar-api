<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Repositories\Article\ArticleRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ArticleController extends Controller
{
    protected ArticleRepositoryInterface $article;

    public function __construct(ArticleRepositoryInterface $article)
    {
        $this->article = $article;
    }

    /**
     * 수집된 자료 리스트 with 게시자 및 미디어 정보
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): Response
    {
        $result = $this->article->getList($request);

        return $this->response($result);
    }


    /**
     * 수집된 자료 상세보기 with 게시자 및 미디어 정보
     *
     * @param Request $request
     * @param $article_id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $article_id): Response
    {
        $result = $this->article->getDetail($request, $article_id);

        return $this->response($result);
    }
}
