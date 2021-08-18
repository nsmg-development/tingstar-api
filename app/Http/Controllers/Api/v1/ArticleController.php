<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Repositories\Article\ArticleDetailRepositoryInterface;
use App\Repositories\Article\ArticleRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ArticleController extends Controller
{
    protected ArticleRepositoryInterface $article;
    protected ArticleDetailRepositoryInterface $articleDetail;

    public function __construct(
        ArticleRepositoryInterface $article,
        ArticleDetailRepositoryInterface  $articleDetail
    )
    {
        $this->article = $article;
        $this->articleDetail = $articleDetail;
    }

    /**
     * 수집된 자료 리스트 with 게시자 및 미디어 정보
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): Response
    {
        $result = $this->article->list($request);

        return $this->response($result);
    }


    /**
     * 수집된 자료 상세보기 with 게시자 및 미디어 정보
     *
     * @param Request $request
     * @param $article_id
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $article_id): Response
    {
        $result = $this->article->show($request, $article_id);

        return $this->response($result);
    }

    /**
     * 수집된 자료 상태 업데이트
     *
     * @param Request $request
     * @param $article_id
     *
     * @return Response
     */
    public function setState(Request $request, $article_id): Response
    {
        $result = $this->article->setState($request, $article_id);

        return $this->response($result);
    }

    /**
     * 수집된 자료 상태 업데이트(멀티)
     *
     * @param Request $request
     *
     * @return Response
     */
    public function setMultipleState(Request $request): Response
    {
        $result = $this->article->setMultipleState($request);

        return $this->response($result);
    }

    /**
     * 수집된 자료 좋아요/싫어요 토글
     *
     * @param Request $request
     * @param $article_id
     * @param $behavior_type
     *
     * @return Response
     */
    public function setArticleBehavior(Request $request, $article_id, $behavior_type): Response
    {
        $result = $this->articleDetail->toggleBehavior($request, $article_id, $behavior_type);

        return $this->response($result);
    }
}
