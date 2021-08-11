<?php

namespace App\Repositories\Platform;

use App\Models\Platform;
use App\Models\PlatformAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PlatformAccountRepository implements PlatformAccountRepositoryInterface
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
     * 플랫폼 로그인 계정 리스트
     *
     * @param Request $request
     *
     * @return Collection
     */
    public function list(Request $request): Collection
    {
        // 유효성 검사
        $validator = Validator::make($request->all(), [
            'platform_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return collect([
                'statusCode' => 400,
                'message' => '파라미터 오류가 발생하였습니다.'
            ]);
        }

        $platformAccounts = $this->platformAccount->where('platform_id', $request->platform_id);

        if (!count($platformAccounts) > 0) {
            return collect([
                'statusCode' => 404,
                'message' => '매체 정보가 존재하지 않습니다.'
            ]);
        }

        return collect(
            [
                'platformAccounts' => $platformAccounts
            ]
        );
    }

    /**
     * 플랫폼 계정 정보 등록
     *
     * @param Request $request
     *
     * @return Collection
     */
    public function store(Request $request): Collection
    {
        // 유효성 검사
        $validator = Validator::make($request->all(), [
            'platform_id' => 'required|integer',
            'login_id' => 'required|string',
            'login_password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return collect([
                'statusCode' => 400,
                'message' => '파라미터 오류가 발생하였습니다.'
            ]);
        }

        try {
            DB::beginTransaction();

            $platformAccount = $this->platformAccount->create([
                'platform_id' => $request->platform_id,
                'login_id' => $request->login_id,
                'login_password' => $request->login_password,
                'token' => $request->token,
                'user_id' => $request->user_id,
            ]);

            DB::commit();

            return collect($platformAccount);
        } catch (\Exception $e) {
            DB::rollBack();

            return collect([
                'statusCode' => 500,
                'message' => '등록중 오류가 발생하였습니다.'
            ]);
        }
    }

    /**
     * 플랫폼 계정정보 업데이트
     *
     * @param Request $request
     * @param int $platform_account_id
     *
     * @return Collection
     */
    public function update(Request $request, $platform_account_id): Collection
    {
        // 유효성 검사
        $validator = Validator::make($request->all(), [
            'platform_id' => 'required|integer',
            'login_id' => 'required|string',
            'login_password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return collect([
                'statusCode' => 400,
                'message' => '파라미터 오류가 발생하였습니다.'
            ]);
        }

        $platformAccount = $this->platformAccount->where('id', $platform_account_id)->first();
        if (!$platformAccount) {
            return collect([
                'statusCode' => 404,
                'message' => '플랫폼 계정 정보를 찾을 수 없습니다.'
            ]);
        }

        try {
            DB::beginTransaction();

            $platformAccount->platform_id = $request->platform_id;
            $platformAccount->login_id = $request->login_id;
            $platformAccount->login_password = $request->login_password;
            $platformAccount->token = $request->token;
            $platformAccount->user_id = $request->user_id;
            $platformAccount->state = $request->state;
            $platformAccount->update();

            DB::commit();

            return collect($platformAccount);
        } catch (\Exception $e) {
            DB::rollBack();

            return collect([
                'statusCode' => 500,
                'message' => '업데이트중 오류가 발생하였습니다.'
            ]);
        }
    }
}
