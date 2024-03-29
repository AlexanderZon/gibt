<?php

namespace App\Models;

use App\Models\AscensionMaterial\FarmingDay;
use App\Models\AscensionMaterial\Type;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AscensionMaterial extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ascension_materials';

    public function characters(){
        return $this->belongsToMany(Character::class, 'character_ascension_materials', 'ascension_material_id', 'character_id');
    }
    
    public function characterSkills(){
        return $this->belongsToMany(Character::class, 'character_skill_ascension_materials', 'ascension_material_id', 'character_id');
    }

    public function weapons(){
        return $this->belongsToMany(Weapon::class, 'weapon_ascension_materials', 'ascension_material_id', 'weapon_id');
    }

    public function ascensionMaterialTypes(){
        return $this->belongsToMany(Type::class, 'ascension_materials_types', 'ascension_material_id', 'type_id');
    }

    public function ascensionMaterialFarmingDays(){
        return $this->hasMany(FarmingDay::class, 'ascension_material_id', 'id');
    }

    public function drops(){
        return $this->belongsToMany(LivingBeing::class, 'ascension_material_drops', 'ascension_material_id', 'living_being_id');
    }

    public function syncFarmingDays($farming_days){
        $this->ascensionMaterialFarmingDays()->delete();
        
        foreach($farming_days as $farming_day){
            if($this->ascensionMaterialFarmingDays()->where('day', '=', $farming_day['day'])->withTrashed()->count() > 0){
                $this->ascensionMaterialFarmingDays()->where('day', '=', $farming_day['day'])->withTrashed()->first()->restore();
            } else {
                $new_farming_day = new FarmingDay();
                $new_farming_day->ascension_material_id = $this->id;
                $new_farming_day->day = $farming_day['day'];
                $new_farming_day->save();
            }
        }
    }
}
