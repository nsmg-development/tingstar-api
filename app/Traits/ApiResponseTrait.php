<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait ApiResponseTrait
{
    /**
     * @param $_result
     * @return array
     */
    public function makeResponse($_result): array
    {
        if ($_result->has('statusCode')) {
            $message = $_result->get('message') ?? $_result->get('msg');
            $result = $this->makeError($_result->get('statusCode'), $message);
        } else {
            $result = $this->makeSuccess($_result, '정상 처리 되었습니다.');
        }

        return $result;
    }

    /**
     * @param $_data
     * @param null $_message
     * @return array
     */
    private function makeSuccess($_data, $_message = null): array
    {
        $result['result'] = true;
        $result['statusCode'] = 200;
        $result['message'] = $_message;
        $result['data'] = $_data;

        return $result;
    }

    /**
     * @param $_statusCode
     * @param $_message
     * @return array
     */
    private function makeError($_statusCode, $_message): array
    {
        $name = 'Error';
        $statusCode_arr = [
            '200' => 'SUCCESS',
            '204' => 'NO CONTENT',
            '400' => 'BAD REQUEST',
            '401' => 'UNAUTHORIZED',
            '403' => 'FORBIDDEN',
            '404' => 'NOT FOUND',
            '405' => 'METHOD NOT ALLOWED',
            '409' => 'CONFLICT',
            '422' => 'UNPROCESSABLE ENTITY',
            '423' => 'LOCKED',
            '500' => 'INTERNAL SERVER ERROR',
        ];

        if (array_key_exists($_statusCode, $statusCode_arr)) {
            $code = $statusCode_arr[$_statusCode];
        } else {
            $code = 'Bad Request';
        }

        $result['result'] = false;
        if ($_statusCode == "200") {
            $name = 'Success';
            $result['result'] = true;
        }

        $result['statusCode'] = $_statusCode;
        $result['message'] = $_message;

        $result['error']['statusCode'] = $_statusCode;
        $result['error']['name'] = $name;
        $result['error']['message'] = $_message;
        $result['error']['code'] = $code;

        return $result;
    }

    /**
     * @param $_statusCode
     * @param null $_message
     * @return array
     */
    private function makeLoginError($_statusCode, $_message = null): array
    {
        $statusCode_arr = [
            '400' => 'BAD REQUEST',
            '401' => 'LOGIN_FAILED',
            '403' => 'EMAIL_NOT_VERIFIED',
            '404' => 'NOT FOUND',
            '405' => 'METHOD NOT ALLOWED',
            '409' => 'CONFLICT',
            '410' => 'UNSUBSCRIBED_USER',
            '422' => 'UNPROCESSABLE ENTITY',
            '423' => 'BANNED_USER',
            '500' => 'INTERNAL SERVER ERROR',
        ];

        $codeMessage_arr = [
            '401' => 'Incorrect email or password',
            '403' => 'Email is not verified',
            '410' => 'User left',
            '423' => 'suspended',
        ];

        if (array_key_exists($_statusCode, $statusCode_arr)) {
            $code = $statusCode_arr[$_statusCode];
        } else {
            $code = 'Bad Request';
        }

        if (array_key_exists($_statusCode, $codeMessage_arr)) {
            $message = $codeMessage_arr[$_statusCode];
        } else {
            $message = 'Bad Request';
        }

        $result['error']['statusCode'] = $_statusCode;
        $result['error']['name'] = 'Error';
        $result['error']['message'] = $message;
        $result['error']['code'] = $code;

        return $result;
    }

    private function viewError($_statusCode)
    {
        $data['statusCode'] = $_statusCode;
        return view('errors.error', $data);
    }
}
