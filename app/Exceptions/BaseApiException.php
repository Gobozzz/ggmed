<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

abstract class BaseApiException extends Exception
{
    protected int $errorCode;

    public function __construct(string $message, int $errorCode, ?int $code = 400)
    {
        parent::__construct($message, $code);
        $this->errorCode = $errorCode;
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'code' => $this->errorCode,
            'message' => $this->getMessage(),
        ], $this->code);
    }

}
