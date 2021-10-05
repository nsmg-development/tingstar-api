<?php

namespace App\Repositories\Article;

use App\Models\Article;
use App\Models\ArticleDetailLog;
use App\Models\ArticleUserFavorite;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\Passport;

class ArticleRepository implements ArticleRepositoryInterface
{
    protected Collection $result;
    protected Media $media;
    protected Article $article;
    protected ArticleUserFavorite $articleUserFavorite;
    protected ArticleDetailLog $articleDetailLog;

    public function __construct(
        Media $media,
        Article $article,
        ArticleUserFavorite $articleUserFavorite,
        ArticleDetailLog $articleDetailLog
    )
    {
        $this->media = $media;
        $this->article = $article;
        $this->articleUserFavorite = $articleUserFavorite;
        $this->articleDetailLog = $articleDetailLog;
    }

    /**
     * 수집된 자료 리스트 with 게시자 및 미디어 정보
     *
     * @param Request $request
     *
     * @return Collection
     */
    public function list(Request $request): Collection
    {
        // 유효성 검사
        $validator = Validator::make($request->all(), [
            'media_id' => 'integer',
            'media_idx' => 'integer',
            'page' => 'integer',
            'per_page' => 'integer',
            'platform' => 'string',
            'search' => 'string',
        ]);

        if ($validator->fails()) {
            return collect([
                'statusCode' => 400,
                'message' => '파라미터 오류가 발생하였습니다.'
            ]);
        }

        // admin 체크
        $isAdmin = false;
        if ($request->is('admin/*') || Auth::user()) {
            $isAdmin = true;
        }

        $media_id = $request->input('media_id', null);
        $media_idx = $request->input('media_idx', null);
        $media = $this->media->where(function ($query) use ($media_id, $media_idx) {
            if ($media_id) {
                $query->where('id', $media_id);
            }
            if ($media_idx) {
                $query->where('media_idx', $media_idx);
            }
        })->first();

        if (!$media) {
            return collect([
                'statusCode' => 404,
                'message' => '매체 정보가 존재하지 않습니다.'
            ]);
        }

        $page = $request->input('page', 1) - 1;
        $perPage = $request->input('per_page', 10);

        $articleModel = $this->article->active($isAdmin)
            ->where([
                'media_id' => $media_id,
                'has_media' => true
            ])
            ->where(function ($query) use ($request, $isAdmin) {
                $state = $request->input('state', null);
                $type = $request->input('type', null);

                if ($isAdmin) {
                    if ($state) {
                        $query->where('state', $state);
                    }

                    if ($type) {
                        $query->where('type', $type);
                    }
                }

                if ($request->has('platform')) {
                    $platform_arr = explode('#', $request->platform);
                    unset($platform_arr[0]);
                    $query->whereIn('platform', $platform_arr);
                }
                if ($request->has('search')) {
                    $search_arr = explode('#', $request->search);
                    unset($search_arr[0]);

                    $query->whereRaw("MATCH(contents, hashtag) AGAINST(? IN BOOLEAN MODE)", array($search_arr));
                }
            })
            ->with(['articleMedias', 'articleOwner', 'articleDetail'])
            ->orderBy('id');

        $articles = $articleModel->paginate($perPage);

        // 즐겨찾기 확인
        $articleUserFavorites = collect($this->articleUserFavorite
            ->where(['media_id' => $media_id, 'user_id' => $request->user_id])
            ->get('article_id'))->groupBy('article_id')->keys();

        // 좋아요 확인
        $articleDetailLogs = collect($this->articleDetailLog
            ->where(['media_id' => $media_id, 'user_id' => $request->user_id, 'type' => 'like'])
            ->get('article_id'))->groupBy('article_id')->keys();

        collect($articles->items())->map(function($item) use ($articleUserFavorites, $articleDetailLogs){
            $item->is_favorite = $articleUserFavorites->contains($item->id);
            $item->is_like = $articleDetailLogs->contains($item->id);
        });

        return collect(
            [
                'totalCount' => $articles->total(),
                'articles' => $articles->items(),
            ]
        );
    }

    /**
     * 수집된 자료 상세보기 with 게시자 및 미디어 정보
     *
     * @param Request $request
     * @param int $article_id
     *
     * @return Collection
     */
    public function show(Request $request, int $article_id): Collection
    {
        $article = $this->article->where('id', $article_id)
            ->with(['articleMedias', 'articleOwner', 'articleDetail', 'articleComments'])
            ->first();

        // 즐겨찾기 확인
        $articleUserFavorite = $this->articleUserFavorite
            ->where(['media_id' => $article->media_id, 'article_id' => $article->id, 'user_id' => $request->user_id])
            ->first();

        // 좋아요 확인
        $articleDetailLog = $this->articleDetailLog
            ->where(['media_id' => $article->media_id, 'article_id' => $article->id, 'user_id' => $request->user_id, 'type' => 'like'])
            ->first();

        $article->is_favorite = (bool) $articleUserFavorite;
        $article->is_like = (bool) $articleDetailLog;

        if (!$article) {
            return collect([
                'statusCode' => 404,
                'message' => '상세 정보가 존재하지 않습니다.'
            ]);
        }

        return collect($article);
    }

    /**
     * 수집된 자료 상태 업데이트
     *
     * @param Request $request
     * @param int $article_id
     *
     * @return Collection
     */
    public function setState(Request $request, int $article_id): Collection
    {
        // 유효성 검사
        $validator = Validator::make($request->all(), [
            'state' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return collect([
                'statusCode' => 400,
                'message' => '파라미터 오류가 발생하였습니다.'
            ]);
        }

        $article = $this->article->where('id', $article_id)->first();
        if (!$article) {
            return collect([
                'statusCode' => 404,
                'message' => '상세 정보가 존재하지 않습니다.'
            ]);
        }

        try {
            DB::beginTransaction();

            $article->state = $request->state;
            $article->update();

            DB::commit();

            return collect($article);
        } catch (\Exception $e) {
            DB::rollBack();

            return collect([
                'statusCode' => 500,
                'message' => '오류가 발생하였습니다.'
            ]);
        }
    }

    public function setMultipleState(Request $request): Collection
    {
        // 유효성 검사
        $validator = Validator::make($request->all(), [
            'article_ids' => 'required|array|min:1',
            'state' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return collect([
                'statusCode' => 400,
                'message' => '파라미터 오류가 발생하였습니다.'
            ]);
        }

        $selectedArticles = $this->article->whereIn('id', $request->article_ids);

        if (!$selectedArticles->count() > 0) {
            return collect([
                'statusCode' => 404,
                'message' => '상세 정보가 존재하지 않습니다.'
            ]);
        }

        try {
            DB::beginTransaction();

            $selectedArticles->update([
                'state' => $request->state
            ]);

            // DB::commit();

            return collect($selectedArticles->get());
        } catch (\Exception $e) {
            DB::rollBack();

            return collect([
                'statusCode' => 500,
                'message' => '오류가 발생하였습니다.'
            ]);
        }
    }
}
