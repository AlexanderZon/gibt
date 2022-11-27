<?php

namespace App\Models\Weapon;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Type extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'weapon_types';

    public function weapons(){
        return $this->hasMany(Weapon::class, 'weapon_type_id', 'id');
    }
}
