<?php

namespace App\Repositories\Article;

use App\Enums\ArticleDetailLogType;
use App\Models\Article;
use App\Models\ArticleDetail;
use App\Models\ArticleDetailLog;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ArticleDetailRepository implements ArticleDetailRepositoryInterface
{
    protected Article $article;
    protected ArticleDetail $articleDetail;
    protected ArticleDetailLog $articleDetailLog;

    public function __construct(Article $article, ArticleDetail $articleDetail, ArticleDetailLog $articleDetailLog)
    {
        $this->article = $article;
        $this->articleDetail = $articleDetail;
        $this->articleDetailLog = $articleDetailLog;
    }

    /**
     * 수집된 자료 좋아요/싫어요 토글
     *
     * @param Request $request
     * @param int $article_id
     * @param string $behavior_type
     *
     * @return Collection
     * @throws \ReflectionException
     */
    public function toggleBehavior(Request $request, $article_id, $behavior_type): Collection
    {
        // 유효성 검사
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return collect([
                'statusCode' => 400,
                'message' => '파라미터 오류가 발생하였습니다.'
            ]);
        }

        // 수집정보 확인
        $this->checkArticle($article_id);

        if (!ArticleDetailLogType::isValidName($behavior_type)) {
            return collect([
                'statusCode' => 400,
                'message' => '행동 양식이 맞지 않습니다.'
            ]);
        }

        $articleDetailLogType = ArticleDetailLogType::getValueByName($behavior_type);

        $articleDetail = $this->articleDetail->where('article_id', $article_id)->first();
        $articleDetailLog = $this->articleDetailLog->where([
            'article_id' => $request->article_id,
            'user_id' => $request->user_id,
            'type' => $articleDetailLogType
        ])->first();

        try {
            DB::beginTransaction();

            if ($articleDetailLog) {
                // 좋아요 한 이력이 존재하면 로그에서 삭제 후 좋아요 -1
                $articleDetailLog->delete();

                $articleDetail->decrement($articleDetailLogType);
            } else {
                // 좋아요 한 이력이 없다면 로그에서 추가 후 좋아요 +1
                $articleDetailLog->create([
                    'article_id' => $request->article_id,
                    'user_id' => $request->user_id,
                    'type' => $articleDetailLogType
                ]);

                $articleDetail->increment($articleDetailLogType);
            }

            DB::commit();

            return collect($articleDetail);
        } catch (\Exception $e) {
            DB::rollBack();

            return collect([
                'statusCode' => 500,
                'message' => '오류가 발생하였습니다.'
            ]);
        }
    }

    /**
     * 수집된 자료 존재 유무 파악
     *
     * @param int $article_id
     */
    private function checkArticle(int $article_id): void
    {
        $article = $this->article->where('id', $article_id)->first();

        if (!$article) {
            collect([
                'statusCode' => 404,
                'message' => '정보가 존재하지 않습니다.'
            ]);
        }
    }

    public function toggleLike(Request $request, $article_id): Collection
    {
        // 수집정보 확인
        $this->checkArticle($article_id);

        $articleDetail = $this->articleDetail->where('article_id', $article_id)->first();
        $articleDetailLog = $this->articleDetailLog->where([
            'article_id' => $request->article_id,
            'user_id' => $request->user_id,
            'type' => ArticleDetailLogType::LIKE
        ])->first();

        try {
            DB::beginTransaction();

            if ($articleDetailLog) {
                // 좋아요 한 이력이 존재하면 로그에서 삭제 후 좋아요 -1
                $articleDetailLog->delete();

                $articleDetail->like--;
            } else {
                // 좋아요 한 이력이 없다면 로그에서 추가 후 좋아요 +1
                $articleDetailLog->create([
                    'article_id' => $request->article_id,
                    'user_id' => $request->user_id,
                    'type' => ArticleDetailLogType::LIKE
                ]);

                $articleDetail->like++;
            }
            $articleDetail->save();

            DB::commit();

            return collect($articleDetail);
        } catch (\Exception $e) {
            DB::rollBack();

            return collect([
                'statusCode' => 500,
                'message' => '오류가 발생하였습니다.'
            ]);
        }
    }

    public function toggleDislike(Request $request, $article_id): Collection
    {
        // 수집정보 확인
        $this->checkArticle($article_id);

        $articleDetail = $this->articleDetail->where('article_id', $article_id)->first();
        $articleDetailLog = $this->articleDetailLog->where([
            'article_id' => $request->article_id,
            'user_id' => $request->user_id,
            'type' => ArticleDetailLogType::DISLIKE
        ])->first();

        try {
            DB::beginTransaction();

            if ($articleDetailLog) {
                // 좋아요 한 이력이 존재하면 로그에서 삭제 후 좋아요 -1
                $articleDetailLog->delete();

                $articleDetail->like--;
            } else {
                // 좋아요 한 이력이 없다면 로그에서 추가 후 좋아요 +1
                $articleDetailLog->create([
                    'article_id' => $request->article_id,
                    'user_id' => $request->user_id,
                    'type' => ArticleDetailLogType::DISLIKE
                ]);

                $articleDetail->like++;
            }
            $articleDetail->save();

            DB::commit();

            return collect($articleDetail);
        } catch (\Exception $e) {
            DB::rollBack();

            return collect([
                'statusCode' => 500,
                'message' => '오류가 발생하였습니다.'
            ]);
        }
    }
}
