<?php

namespace App\Models\Account;

use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CharacterList extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'account_characters_list';

    public function accountCharacter(){
        return $this->belongsTo(Character::class, 'account_character_id', 'id');
    }

    public function account(){
        return $this->belongsTo(Account::class, 'account_id', 'id');
    }
}
