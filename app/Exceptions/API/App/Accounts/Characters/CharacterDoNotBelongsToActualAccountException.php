<?php

namespace App\Exceptions\API\App\Accounts\Characters;

use App\Exceptions\HandledException;
use Exception;

class CharacterDoNotBelongsToActualAccountException extends HandledException
{
    public function __construct(){
        parent::__construct("Character is forbidden");
    }
}
