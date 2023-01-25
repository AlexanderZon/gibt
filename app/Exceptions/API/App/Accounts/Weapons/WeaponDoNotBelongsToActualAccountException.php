<?php

namespace App\Exceptions\API\App\Accounts\Weapons;

use App\Exceptions\HandledException;

class WeaponDoNotBelongsToActualAccountException extends HandledException
{
    public function __construct(){
        parent::__construct("Weapon is forbidden");
    }
}
