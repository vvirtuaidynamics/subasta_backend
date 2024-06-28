<?php

namespace App\Enums;
use Symfony\Component\HttpFoundation\Response;

class ApiResponseCodes
{
    const HTTP_SUCCESS = 200;
    const HTTP_CREATED = Response::HTTP_CREATED;
    const HTTP_ACCEPTED = Response::HTTP_ACCEPTED;
    const HTTP_NON_AUTHORITATIVE_INFORMATION = Response::HTTP_NON_AUTHORITATIVE_INFORMATION;
    const HTTP_NO_CONTENT = Response::HTTP_NO_CONTENT;
    const HTTP_RESET_CONTENT = Response::HTTP_RESET_CONTENT;
    const HTTP_PARTIAL_CONTENT = Response::HTTP_PARTIAL_CONTENT;
    const HTTP_ALREADY_REPORTED = Response::HTTP_ALREADY_REPORTED;
    const HTTP_BAD_REQUEST = Response::HTTP_BAD_REQUEST;
    const HTTP_UNAUTHORIZED = Response::HTTP_UNAUTHORIZED;
    const HTTP_PAYMENT_REQUIRED = Response::HTTP_PAYMENT_REQUIRED;
    const HTTP_FORBIDDEN = Response::HTTP_FORBIDDEN;
    const HTTP_NOT_FOUND = Response::HTTP_NOT_FOUND;
    const HTTP_METHOD_NOT_ALLOWED = Response::HTTP_METHOD_NOT_ALLOWED;
    const HTTP_NOT_ACCEPTABLE = Response::HTTP_NOT_ACCEPTABLE;
    const HTTP_PROXY_AUTHENTICATION_REQUIRED = Response::HTTP_PROXY_AUTHENTICATION_REQUIRED;
    const HTTP_REQUEST_TIMEOUT = Response::HTTP_REQUEST_TIMEOUT;
    const HTTP_CONFLICT = Response::HTTP_CONFLICT;
    const HTTP_REQUEST_URI_TOO_LONG = Response::HTTP_REQUEST_URI_TOO_LONG;
    const HTTP_UNSUPPORTED_MEDIA_TYPE = Response::HTTP_UNSUPPORTED_MEDIA_TYPE;
    const HTTP_UNPROCESSABLE_CONTENT = Response::HTTP_UNPROCESSABLE_ENTITY;
    const HTTP_INTERNAL_SERVER_ERROR = Response::HTTP_INTERNAL_SERVER_ERROR;
    const HTTP_NOT_IMPLEMENTED = Response::HTTP_NOT_IMPLEMENTED;
    const HTTP_BAD_GATEWAY = Response::HTTP_BAD_GATEWAY;
    const HTTP_SERVICE_UNAVAILABLE = Response::HTTP_SERVICE_UNAVAILABLE;
    const HTTP_NETWORK_AUTHENTICATION_REQUIRED = Response::HTTP_NETWORK_AUTHENTICATION_REQUIRED;

}