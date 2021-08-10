<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Collection;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param AuthenticationException $exception
     *
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function unauthenticated($request, AuthenticationException $exception){
       // if($request->expectsJson()){
       //     return response()->json('Please login',401);
       // }
       //
       // abort('401');

        $result = $this->makeError(401, 'unauthorized', 'Unauthorized');

        return response()->jsonp($request->input('callback'), $result, $result['statusCode']);
    }

    /**
     * @param $request
     * @param AuthorizationException $exception
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function unauthorized($request, AuthorizationException $exception){
       // if($request->expectsJson()){
       //     return response()->json("You don't have permission to do this",401);
       // }
       //
       // abort('403');

        $result = $this->makeError(403, 'forbidden', 'Forbidden');

        return response()->jsonp($request->input('callback'), $result, $result['statusCode']);
    }

    private function makeError(int $statusCode, string $message, string $code): Collection
    {
        $result['statusCode'] = $statusCode;
        $result['message'] = $message;

        $result['error']['statusCode'] = $statusCode;
        $result['error']['name'] = "Error";
        $result['error']['message'] = $message;
        $result['error']['code'] = $code;

        return collect($result);
    }
}
