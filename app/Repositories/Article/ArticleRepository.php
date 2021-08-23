<?php

namespace App\Repositories\Article;

use App\Models\Article;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
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

        $articleModel = $this->article->active()
            ->where([
                'media_id' => $media_id,
                'has_media' => true
            ])
            ->where(function ($query) use ($request) {
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
            ->with(['articleMedias', 'articleDetail'])
            ->orderBy('id');

        $articles = $articleModel->paginate($perPage);

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
            ->with(['articleOwner', 'articleMedias'])
            ->first();

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
