<?php

namespace App\Repositories\Keyword;

use App\Models\Keyword;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KeywordRepository implements KeywordRepositoryInterface
{
    protected Collection $result;
    protected Media $media;
    protected Keyword $keyword;

    public function __construct(Media $media, Keyword $keyword)
    {
        $this->media = $media;
        $this->keyword =$keyword;
    }

    /**
     * 매체 키워드 등록
     *
     * @param Request $request
     *
     * @return Collection
     */
    public function store(Request $request): Collection
    {
        // 유효성 검사
        $validator = Validator::make($request->all(), [
            'media_id' => 'required|integer',
            'keyword' => 'required|string',
            'platform' => 'required|string',
        ]);

        if ($validator->fails()) {
            return collect([
                'statusCode' => 400,
                'message' => '파라미터 오류가 발생하였습니다.'
            ]);
        }

        // 매체 존재 확인
        $media = $this->media->where('id', $request->media_id)->first();
        if (!$media) {
            return collect([
                'statusCode' => 404,
                'message' => '매체 정보가 존재하지 않습니다.'
            ]);
        }

        try {
            DB::beginTransaction();

            $keyword = $this->keyword->create([
                'media_id' => $request->media_id,
                'keyword' => $request->keyword,
                'platform' => $request->platform,
            ]);

            DB::commit();

            return collect($keyword);
        } catch (\Exception $e) {
            DB::rollBack();

            return collect([
                'statusCode' => 500,
                'message' => '오류가 발생하였습니다.'
            ]);
        }
    }

    /**
     * 매체 키워드 업데이트
     *
     * @param Request $request
     * @param int $keyword_id
     *
     * @return Collection
     */
    public function update(Request $request, int $keyword_id): Collection
    {
        // 유효성 검사
        $validator = Validator::make($request->all(), [
            'media_id' => 'required|integer',
            'keyword' => 'required|string',
            'platform' => 'required|string',
            'state' => 'integer',
        ]);

        if ($validator->fails()) {
            return collect([
                'statusCode' => 400,
                'message' => '파라미터 오류가 발생하였습니다.'
            ]);
        }

        // 매체사  키워드정보 확인
        $keyword = $this->keyword->where('id', $keyword_id)->first();
        if (!$keyword) {
            return collect([
                'statusCode' => 404,
                'message' => '매체 키워드 정보를 찾을 수 없습니다.'
            ]);
        }

        try {
            DB::beginTransaction();

            $keyword->media_id = $request->media_id;
            $keyword->keyword = $request->keyword;
            $keyword->platform = $request->platform;
            $keyword->update();

            DB::commit();

            return collect($keyword);
        } catch (\Exception $e) {
            DB::rollBack();

            return collect([
                'statusCode' => 500,
                'message' => '업데이트중 오류가 발생하였습니다.'
            ]);
        }
    }
}
