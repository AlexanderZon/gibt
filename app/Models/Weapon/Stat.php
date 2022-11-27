<?php

namespace App\Models\Weapon;

use App\Models\AscensionMaterial;
use App\Models\Stat\Type;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stat extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'weapon_stats';

    public function variableStat(){
        return $this->belongsTo(Type::class, 'variable_stat_id', 'id');
    }

    public function weaponPrimaryMaterial(){
        return $this->belongsTo(AscensionMaterial::class, 'weap_primary_material_id', 'id');
    }

    public function weaponSecondaryMaterial(){
        return $this->belongsTo(AscensionMaterial::class, 'weap_secondary_material_id', 'id');
    }

    public function weaponCommonItem(){
        return $this->belongsTo(AscensionMaterial::class, 'weap_common_item_id', 'id');
    }
}
