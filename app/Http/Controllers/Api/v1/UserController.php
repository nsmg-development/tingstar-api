<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepositoryInterface;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class UserController extends Controller
{
    use ApiResponseTrait;

    protected Collection $result;
    protected UserRepositoryInterface $user;

    public function __construct(UserRepositoryInterface $user)
    {
        $this->result = new Collection();
        $this->user = $user;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): Response
    {
        $result = $this->user->store($request);

        return $this->response($result);
    }
}
