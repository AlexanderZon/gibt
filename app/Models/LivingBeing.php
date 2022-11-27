<?php

namespace App\Models;

use App\Models\LivingBeing\ClassType;
use App\Models\LivingBeing\Grade;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LivingBeing extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'living_beings';

    public function drops(){
        return $this->belongsToMany(AscensionMaterial::class, 'ascension_material_drops', 'living_being_id', 'ascension_material_id');
    }

    public function livingBeingGrade(){
        return $this->belongsTo(Grade::class, 'living_being_grade_id', 'id');
    }

    public function livingBeingClass(){
        return $this->belongsTo(ClassType::class, 'living_being_class_id', 'id');
    }
}
