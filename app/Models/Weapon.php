<?php

namespace App\Models;

use App\Models\Stat\Type as StatType;
use App\Models\Weapon\Image;
use App\Models\Weapon\Stat;
use App\Models\Weapon\Type;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Weapon extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'weapons';

    public function weaponImages(){
        return $this->hasMany(Image::class, 'weapon_id', 'id');
    }

    public function weaponType(){
        return $this->belongsTo(Type::class, 'weapon_type_id', 'id');
    }

    public function substatType(){
        return $this->belongsTo(StatType::class, 'substat_type_id', 'id');
    }

    public function ascensionMaterials(){
        return $this->belongsToMany(AscensionMaterial::class, 'weapon_ascension_materials', 'weapon_id', 'ascension_material_id');
    }

    public function weaponStats(){
        return $this->hasMany(Stat::class, 'weapon_id', 'id');
    }
}
