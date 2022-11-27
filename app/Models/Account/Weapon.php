<?php

namespace App\Models\Account;

use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Weapon extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'account_weapons';

    public function account(){
        return $this->belongsTo(Account::class, 'account_id', 'id');
    }

    public function weapon(){
        return $this->belongsTo(Weapon::class, 'weapon_id');
    }

    public function accountCharacter(){
        return $this->hasOne(Character::class, 'account_weapon_id');
    }
}
