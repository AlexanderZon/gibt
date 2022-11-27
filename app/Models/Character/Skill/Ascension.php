<?php

namespace App\Models\Character\Skill;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ascension extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'character_skill_ascensions';

    public function talentBook(){
        return $this->belongsTo(AscensionMaterial::class, 'talent_book_id', 'id');
    }

    public function charCommonItem(){
        return $this->belongsTo(AscensionMaterial::class, 'char_common_item_id', 'id');
    }

    public function talentBossItem(){
        return $this->belongsTo(AscensionMaterial::class, 'talent_boss_item_id', 'id');
    }
}
