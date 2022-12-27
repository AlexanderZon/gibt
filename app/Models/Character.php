<?php

namespace App\Models;

use App\Models\Character\Image;
use App\Models\AscensionMaterial;
use App\Models\Character\Skill\AscensionMaterial as SkillAscensionMaterial;
use App\Models\Character\Stat;
use App\Models\Weapon\Type;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Character extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'characters';

    public function vision(){
        return $this->belongsTo(Vision::class, 'vision_id', 'id');
    }

    public function element(){
        return $this->belongsTo(Element::class, 'element_id', 'id');
    }

    public function association(){
        return $this->belongsTo(Association::class, 'association_id', 'id');
    }

    public function weaponType(){
        return $this->belongsTo(Type::class, 'weapon_type_id', 'id');
    }

    public function characterImages(){
        return $this->hasMany(Image::class, 'character_id', 'id');
    }

    public function characterIcon(){
        return $this->hasOne(Image::class, 'character_id', 'id')->where('type','=','icon');
    }

    public function characterSideIcon(){
        return $this->hasOne(Image::class, 'character_id', 'id')->where('type','=','side_icon');
    }

    public function characterGachaCard(){
        return $this->hasOne(Image::class, 'character_id', 'id')->where('type','=','gacha_card');
    }

    public function characterGachaSplash(){
        return $this->hasOne(Image::class, 'character_id', 'id')->where('type','=','gacha_splash');
    }

    public function ascensionMaterials(){
        return $this->belongsToMany(AscensionMaterial::class, 'character_ascension_materials', 'character_id', 'ascension_material_id');
    }

    public function skillAscensionMaterials(){
        return $this->belongsToMany(AscensionMaterial::class, 'character_skill_ascension_materials', 'character_id', 'ascension_material_id');
    }

    public function characterSkillAscensions(){
        return $this->hasMany(SkillAscensionMaterial::class, 'character_id', 'id');
    }

    public function characterStats(){
        return $this->hasMany(Stat::class, 'character_id', 'id');
    }
}
