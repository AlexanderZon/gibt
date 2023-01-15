<?php

namespace App\Exceptions;

use Exception;

class HandledException extends Exception
{
    
    public function __construct($message){
        parent::__construct($message);
    }

    public function render($request)
    {
        return response([
            'exception' => get_class($this), 
            'message' => $this->message    
        ], 500);
    }
}
