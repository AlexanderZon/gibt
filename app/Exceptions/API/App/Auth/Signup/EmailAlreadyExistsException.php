<?php

namespace App\Exceptions\API\App\Auth\Signup;

use Exception;

class EmailAlreadyExistsException extends Exception
{
    public function __construct($email){
        parent::__construct("Email $email already exists");
    }
}
