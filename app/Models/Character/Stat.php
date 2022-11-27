<?php

namespace App\Models\Character;

use App\Models\AscensionMaterial;
use App\Models\Character;
use App\Models\Stat\Type;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stat extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'character_stats';

    public function character(){
        return $this->balongsTo(Character::class, 'character_id', 'id');
    }

    public function variableStat(){
        return $this->belongsTo(Type::class, 'variable_stat_id', 'id');
    }

    public function charJewel(){
        return $this->belongsTo(AscensionMaterial::class, 'char_jewel_id', 'id');
    }

    public function charElementalStone(){
        return $this->belongsTo(AscensionMaterial::class, 'char_elemental_stone_id', 'id');
    }

    public function charLocalMaterial(){
        return $this->belongsTo(AscensionMaterial::class, 'char_local_material_id', 'id');
    }

    public function charCommonItem(){
        return $this->belongsTo(AscensionMaterial::class, 'char_common_item_id', 'id');
    }
}
