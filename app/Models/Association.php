<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Association extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'associations';

    public function characters(){
        return $this->hasMany(Character::class, 'character_id', 'id');
    }
}
