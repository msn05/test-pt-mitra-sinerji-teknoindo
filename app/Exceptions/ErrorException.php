<?php

namespace App\Exceptions;

use Exception;

class ErrorException extends Exception
{
    public function render($request)
    {
        return response()->json([
            'errors' => [$this->getMessage()]
        ], 401);
    }
}
