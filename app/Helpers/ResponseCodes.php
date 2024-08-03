<?php
namespace App\Helpers;

class ResponseCodes{
    const OK = [
        'status' => 'Request Success',
        'code'   =>  200
    ];

    const CREATED = [
        'status' => 'Resource Created',
        'code'   => 201
    ];

    const ACCEPTED = [
        'status' => 'Request Accepted',
        'code'   => 202
    ];

    const NO_CONTENT = [
        'status' => 'No Content',
        'code'   => 204
    ];

    const MOVED_PERMANENTLY = [
        'status' => 'Moved Permanently',
        'code'   => 301
    ];

    const FOUND = [
        'status' => 'Found',
        'code'   => 302
    ];

    const SEE_OTHER = [
        'status' => 'See Other',
        'code'   => 303
    ];

    const NOT_MODIFIED = [
        'status' => 'Not Modified',
        'code'   => 304
    ];

    const TEMPORARY_REDIRECT = [
        'status' => 'Temporary Redirect',
        'code'   => 307
    ];

    const PERMANENT_REDIRECT = [
        'status' => 'Permanent Redirect',
        'code'   => 308
    ];

    const BAD_REQUEST = [
        'status' => 'Bad Request',
        'code'   => 400
    ];

    const UNAUTHORIZED = [
        'status' => 'Unauthorized',
        'code'   => 401
    ];

    const FORBIDDEN = [
        'status' => 'Forbidden',
        'code'   => 403
    ];

    const NOT_FOUND = [
        'status' => 'Not Found',
        'code'   => 404
    ];

    const METHOD_NOT_ALLOWED = [
        'status' => 'Method Not Allowed',
        'code'   => 405
    ];

    const CONFLICT = [
        'status' => 'Conflict',
        'code'   => 409
    ];

    const PRECONDITION_FAILED = [
        'status' => 'Precondition Failed',
        'code'   => 412
    ];

    const UNSUPPORTED_MEDIA_TYPE = [
        'status' => 'Unsupported Media Type',
        'code'   => 415
    ];

    const UNPROCESSABLE_ENTITY = [
        'status' => 'Unprocessable Entity',
        'code'   => 422
    ];

    const TOO_MANY_REQUESTS = [
        'status' => 'Too Many Requests',
        'code'   => 429
    ];

    const INTERNAL_SERVER_ERROR = [
        'status' => 'Internal Server Error',
        'code'   => 500
    ];

    const NOT_IMPLEMENTED = [
        'status' => 'Not Implemented',
        'code'   => 501
    ];

    const SERVICE_UNAVAILABLE = [
        'status' => 'Service Unavailable',
        'code'   => 503
    ];
}