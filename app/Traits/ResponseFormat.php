<?php

namespace App\Traits;

use App\Helpers\ResponseCodes;

trait ResponseFormat
{
    public static function getMessage($status)
    {
        return $status['status'];
    }

    public static function getCode($status)
    {
        return $status['code'];
    }

    function sendResponse($result, $status = ResponseCodes::OK, $arg = [])
    {
        $response = [
            'status' => self::getMessage($status),
            'code'   => self::getCode($status),
        ];

        if (!empty($arg)) {
            $response = array_merge($response, $arg);
        }

        $result = ['data' => isset($result) ? $result : null];
        $response = array_merge($response, $result);

        return response()->json($response, self::getCode($status));
    }

    function sendError($errorMessages = [], $status = ResponseCodes::NOT_FOUND)
    {
        $response = [
            'status' => self::getMessage($status),
            'code'   => self::getCode($status),
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, self::getCode($status));
    }
}
