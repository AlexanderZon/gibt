<?php

namespace App\Models;

use App\Models\Account\Character;
use App\Models\Account\CharacterList;
use App\Models\Account\Weapon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'accounts';

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function accountCharacters(){
        return $this->hasMany(Character::class, 'account_id', 'id');
    }

    public function accountWeapons(){
        return $this->hasMany(Weapon::class, 'account_id', 'id');
    }

    public function accountCharacterList(){
        return $this->hasMany(CharacterList::class, 'account_id', 'id')->orderBy('order', 'ASC');
    }

    public function accountCharacterToBuild(){
        return $this->belongsToMany(Character::class, 'account_character_list', 'account_id', 'account_character_id');
    }
}
