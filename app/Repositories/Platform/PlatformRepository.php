<?php

namespace App\Repositories\Platform;

use App\Models\Platform;
use App\Models\PlatformAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PlatformRepository implements PlatformRepositoryInterface
{
    protected Collection $result;
    protected Platform $platform;
    protected PlatformAccount $platformAccount;

    public function __construct(Platform $platform, PlatformAccount $platformAccount)
    {
        $this->platform = $platform;
        $this->platformAccount = $platformAccount;
    }

    /**
     * 플랫폼 리스트 with 플랫폼 로그인 계정 정보
     *
     * @param Request $request
     *
     * @return Collection
     */
    public function list(Request $request): Collection
    {
        $platforms = $this->platform->with('platformAccounts')->get();

        if (!count($platforms) > 0) {
            return collect([
                'statusCode' => 404,
                'message' => '플랫폼 정보가 존재하지 않습니다.'
            ]);
        }

        return collect(
            [
                'platforms' => $platforms
            ]
        );
    }

    /**
     * 플랫폼 상세보기 with 플랫폼 로그인 계정 정보
     *
     * @param Request $request
     * @param int $platform_id
     *
     * @return Collection
     */
    public function show(Request $request, int $platform_id): Collection
    {
        $platform = $this->platform->where('id', $platform_id)
            ->with('platformAccounts')
            ->first();

        if (!$platform) {
            return collect([
                'statusCode' => 404,
                'message' => '상세 정보가 존재하지 않습니다.'
            ]);
        }

        return collect($platform);
    }
}
