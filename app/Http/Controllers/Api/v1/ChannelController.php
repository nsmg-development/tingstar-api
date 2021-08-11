<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Repositories\Channel\ChannelRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ChannelController extends Controller
{
    protected ChannelRepositoryInterface $channel;

    public function __construct(ChannelRepositoryInterface $channel)
    {
        $this->channel = $channel;
    }

    /**
     * 매체 채널 정보 등록
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request): Response
    {
        $result = $this->channel->store($request);

        return $this->response($result);
    }

    /**
     * 매체 채널 정보 업데이트
     *
     * @param Request $request
     * @param $channel_id
     *
     * @return Response
     */
    public function update(Request $request, $channel_id): Response
    {
        $result = $this->channel->update($request, $channel_id);

        return $this->response($result);
    }
}
