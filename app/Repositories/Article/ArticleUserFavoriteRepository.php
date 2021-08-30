<?php

namespace App\Repositories\Article;

use App\Models\ArticleUserFavorite;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ArticleUserFavoriteRepository implements ArticleUserFavoriteRepositoryInterface
{
    protected Media $media;
    protected ArticleUserFavorite $articleUserFavorite;

    public function __construct(Media $media, ArticleUserFavorite $articleUserFavorite)
    {
        $this->media = $media;
        $this->articleUserFavorite = $articleUserFavorite;
    }

    public function list(Request $request): Collection
    {
        // 유효성 검사
        $validator = Validator::make($request->all(), [
            'media_id' => 'required|integer',
            'user_id' => 'required|string',
            'page' => 'integer',
            'per_page' => 'integer'
        ]);

        if ($validator->fails()) {
            return collect([
                'statusCode' => 400,
                'message' => '파라미터 오류가 발생하였습니다.'
            ]);
        }

        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);

        $media_id = $request->input('media_id', null);
        $media = $this->media->where('id', $media_id)->first();
        if (!$media) {
            return collect([
                'statusCode' => 404,
                'message' => '매체 정보가 존재하지 않습니다.'
            ]);
        }

        $articleUserFavorites = $this->articleUserFavorite->where([
            'media_id' => $request->media_id,
            'user_id' => $request->user_id
        ])
        ->with(['article.articleMedias', 'article.articleOwner', 'article.articleDetail'])
        ->orderByDesc('created_at');

        $articles = $articleUserFavorites->paginate($perPage);

        return collect(
            [
                'totalCount' => $articles->total(),
                'favorites' => $articles->items(),
            ]
        );
    }

    public function setFavorite(Request $request): Collection
    {
        // 유효성 검사
        $validator = Validator::make($request->all(), [
            'media_id' => 'required|integer',
            'user_id' => 'required|string',
            'article_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return collect([
                'statusCode' => 400,
                'message' => '파라미터 오류가 발생하였습니다.'
            ]);
        }

        $media_id = $request->input('media_id', null);
        $media = $this->media->where('id', $media_id)->first();
        if (!$media) {
            return collect([
                'statusCode' => 404,
                'message' => '매체 정보가 존재하지 않습니다.'
            ]);
        }

        $articleUserFavorite = $this->articleUserFavorite->where([
            'media_id' => $request->media_id,
            'user_id' => $request->user_id,
            'article_id' => $request->article_id
        ])->first();

        try {
            DB::beginTransaction();

            // 즐겨찾기에 이미 등록여부에 따라 삭제 또는 등록
            if ($articleUserFavorite) {
                $articleUserFavorite->delete();
            } else {
                $articleUserFavorite = $this->articleUserFavorite->create([
                    'media_id' => $request->media_id,
                    'user_id' => $request->user_id,
                    'article_id' => $request->article_id,
                ]);
            }

            DB::commit();

            return collect($articleUserFavorite);
        } catch (\Exception $e) {
            DB::rollBack();

            return collect([
                'statusCode' => 500,
                'message' => '오류가 발생하였습니다.'
            ]);
        }
    }
}
