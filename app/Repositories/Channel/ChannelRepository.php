<?php

namespace App\Repositories\Channel;

use App\Models\Channel;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ChannelRepository implements ChannelRepositoryInterface
{
    protected Collection $result;
    protected Media $media;
    protected Channel $channel;

    public function __construct(Media $media, Channel $channel)
    {
        $this->media = $media;
        $this->channel = $channel;
    }

    /**
     * 매체 채널 정보 생성하기
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
            'platform' => 'required|string',
            'name' => 'required|string',
            'channel' => 'required|string',
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

            $channel = $this->channel->create([
                'media_id' => $request->media_id,
                'platform' => $request->platform,
                'name' => $request->name,
                'channel' => $request->channel,
            ]);

            DB::commit();

            return collect($channel);
        } catch (\Exception $e) {
            DB::rollBack();

            return collect([
                'statusCode' => 500,
                'message' => '오류가 발생하였습니다.'
            ]);
        }
    }

    /**
     * 매체 채널 정보 업데이트
     *
     * @param Request $request
     * @param int $channel_id
     *
     * @return Collection
     */
    public function update(Request $request, int $channel_id): Collection
    {
        // 유효성 검사
        $validator = Validator::make($request->all(), [
            'media_id' => 'required|integer',
            'platform' => 'required|string',
            'name' => 'required|string',
            'channel' => 'required|string',
            'state' => 'integer',
        ]);

        if ($validator->fails()) {
            return collect([
                'statusCode' => 400,
                'message' => '파라미터 오류가 발생하였습니다.'
            ]);
        }

        // 매체사 채널정보 확인
        $channel = $this->channel->where('id', $channel_id)->first();
        if (!$channel) {
            return collect([
                'statusCode' => 404,
                'message' => '매체 채널 정보를 찾을 수 없습니다.'
            ]);
        }

        try {
            DB::beginTransaction();

            tap($channel)->update([
                'media_id' => $request->media_id,
                'platform' => $request->platform,
                'name' => $request->name,
                'channel' => $request->channel,
                'state' => $request->state ?? $channel->state,
            ]);

            DB::commit();

            return collect($channel);
        } catch (\Exception $e) {
            DB::rollBack();

            return collect([
                'statusCode' => 500,
                'message' => '업데이트중 오류가 발생하였습니다.'
            ]);
        }
    }
}
