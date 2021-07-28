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

    public function get(Request $request): Collection
    {
        // 유효성 검사
        $validator = Validator::make($request->all(), [
            'media_idx' => 'required|integer',
            'page' => 'integer',
            'per_page' => 'integer'
        ]);

        if ($validator->fails()) {
            return collect([
                'statusCode' => 400,
                'message' => '파라미터 오류가 발생하였습니다.'
            ]);
        }

        $media = $this->media->byMediaIdx($request->media_idx)->first();
        if (!$media) {
            return collect([
                'statusCode' => 404,
                'message' => '매체 정보가 존재하지 않습니다.'
            ]);
        }

        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);

        $articleModel = $this->article->active()
            ->where('media_id', $media->id)
            // ->with(['articleOwner', 'articleMedias']);
            ->with('articleMedias');

        $totalCount = $articleModel->count();
        $articles = $articleModel->forPage($page, $perPage)->get();

        return collect(
            [
                'totalCount' => $articleModel->count(),
                'articles' => $articles
            ]
        );
    }
}
