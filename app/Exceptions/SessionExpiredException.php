<?php

namespace App\Exceptions;

use Exception;

class SessionExpiredException extends Exception
{
    public function render($request)
    {
        return redirect()->route('login')->with('message', 'Session expired. Please login again.');
    }
}
