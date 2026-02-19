<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

abstract class BaseException extends Exception
{
    protected int $errorCode;

    protected int $httpStatus;

    public function __construct(string $message, int $errorCode, int $httpStatus = 400)
    {
        parent::__construct($message);
        $this->errorCode = $errorCode;
        $this->httpStatus = $httpStatus;
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'code' => $this->errorCode,
            'message' => $this->getMessage(),
        ], $this->httpStatus);
    }
}
