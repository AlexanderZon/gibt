<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Element extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'elements';

    public function characters(){
        return $this->hasMany(Character::class, 'element_id', 'id');
    }
}
