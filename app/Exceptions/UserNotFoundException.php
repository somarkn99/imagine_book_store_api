<?php

namespace App\Exceptions;

use Exception;

class UserNotFoundException extends Exception
{
    public function render($request)
    {
        return response()->json([
            'status' => 'error',
            'message' => 'User not found',
        ], 404);
    }
}
