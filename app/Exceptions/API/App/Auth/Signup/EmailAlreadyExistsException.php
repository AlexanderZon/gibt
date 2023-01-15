<?php

namespace App\Exceptions\API\App\Auth\Signup;

use App\Exceptions\HandledException;

class EmailAlreadyExistsException extends HandledException
{
    public function __construct($email){
        parent::__construct("E-mail `$email` already exists");
    }
}
