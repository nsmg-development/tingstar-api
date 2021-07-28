<?php

namespace App\Repositories\Article;

use App\Models\Article;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class ArticleRepository implements ArticleRepositoryInterface
{
    protected Collection $result;
    protected Media $media;
    protected Article $article;

    public function __construct(Media $media, Article $article)
    {
        $this->media = $media;
        $this->article = $article;
    }

    /**
     * 수집된 자료 리스트 with 게시자 및 미디어 정보
     *
     * @param Request $request
     * @return Collection
     */
    public function get(Request $request): Collection
    {
        // 유효성 검사
        $validator = Validator::make($request->all(), [
            'media_id' => 'integer',
            'media_idx' => 'integer',
            'page' => 'integer',
            'per_page' => 'integer'
        ]);

        if ($validator->fails()) {
            return collect([
                'statusCode' => 400,
                'message' => '파라미터 오류가 발생하였습니다.'
            ]);
        }

        $media_id = $request->input('media_id', null);
        if ($request->has('media_idx') && !$media_id) {
            $media = $this->media->where([
                'media_idx' => $request->media_idx
            ])->first();

            $media_id = $media->id;
        }

        if (!$media_id) {
            return collect([
                'statusCode' => 404,
                'message' => '매체 정보가 존재하지 않습니다.'
            ]);
        }

        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);

        $articleModel = $this->article->active()
            ->where('media_id', $media_id)
            ->with(['articleOwner', 'articleMedias'])
            ->orDoesntHave('articleOwner','articleMedias');

        $totalArticles = $articleModel->get();
        $totalCount = $totalArticles->count();

        $articles = $articleModel->forPage($page, $perPage)->get();

        return collect(
            [
                'totalCount' => $totalCount,
                'articles' => $articles
            ]
        );
    }
}
