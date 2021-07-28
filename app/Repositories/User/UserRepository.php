<?php

namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use MrAtiebatie\Repository;

class UserRepository implements UserRepositoryInterface
{
    use Repository;

    protected Collection $result;
    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param Request $request
     * @return Collection
     */
    public function store(Request $request): Collection
    {
        // 유효성 검사
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|max:255',
            'password' => 'required|string',  // need password_confirmation field
        ]);

        if ($validator->fails()) {
            return collect([
                'statusCode' => 400,
                'message' => '파라미터 오류가 발생하였습니다.'
            ]);
        }

        // 기가입 유저 체크
        $user = $this->user->where('email', $request->email)->first();
        if ($user) {
            return collect([
                'statusCode' => 409,
                'message' => '이미 가입된 회원입니다.'
            ]);
        }

        // 비밀번호 확인 체크
        if ($request->password !== $request->password_confirmation) {
            return collect([
                'statusCode' => 400,
                'message' => '비밀번호가 일치 하지 않습니다.'
            ]);
        }

        try {
            DB::beginTransaction();

            $user = $this->user->create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            DB::commit();

            return $user;
        } catch (\Exception $e) {
            DB::rollBack();

            return collect([
                'statusCode' => 500,
                'message' => '오류가 발생하였습니다.'
            ]);
        }
    }
}
