<?php

namespace App\Enums;

class ApiResponseMessages
{
    const RETRIEVED_SUCCESSFULLY = 'Records retrieved successfully';
    const CREATED_SUCCESSFULLY = 'Record created successfully';
    const FETCHED_SUCCESSFULLY = 'Record fetched successfully';
    const UPDATED_SUCCESSFULLY = 'Record updated successfully';
    const DELETED_SUCCESSFULLY = 'Record deleted successfully';
    const TRASHED_SUCCESSFULLY = 'Record trashed successfully';
    const VALIDATION_FAILED = 'Validation failed';
    const NO_QUERY_RESULTS = 'No record found';
    const NON_NUMERIC_ID = 'Provided id is not numeric';
    const FORBIDDEN = 'Forbidden | Insufficient rights to access this resource';
    const UNAUTHORIZED = 'Unauthorized';
    const LOGGED_OUT_SUCCESSFULLY = 'Successfully logged out';
    const INTERNAL_SERVER_ERROR = "Internal server error";
    const UNPROCESSABLE_CONTENT = "Unprocessable content";
    const UNAUTHORIZED_DOMAIN_OR_IP = "Unauthorized domain or IP";
    const LOGIN_SUCCESSFUL = "Login successful";
    const RESET_PASSWORD_SUCCESSFUL = "Password reset successfully";
    const OTP_SUCCESSFUL = "OTP sent successfully to ";
    const OTP_VERIFIED = "OTP verified successfully";
    const INVALID_USER = "Invalid user";
    const INVALID_CREDENTIALS = "Invalid credentials";
    const RESOURCE_NOT_FOUND = "Resource not found";
    const METHOD_NOT_ALLOWED = "Method not allowed for this endpoint.";
    const DB_QUERY_ERROR = "Database query error";
    const NOT_ACCEPTABLE  = "Response format not acceptable";
    const TOO_MANY_ATTEMPT = "Too many requests";
    const DUPLICATE_ENTRY = "Duplicate entry - The resource already exists.";
    const BAD_REQUEST = "Bad request.";
    const UNSUPPORTED_MEDIA_TYPE = "Unsupported media type";
    const SERVICE_UNAVAILABLE = "Service unavailable";
}
