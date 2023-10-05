<?php

namespace App\Exceptions;

use Exception;

class UnauthorizedException extends Exception
{
    public function render($request)
    {
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized',
        ], 401);
    }
}
