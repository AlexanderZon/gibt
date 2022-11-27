<?php

namespace App\Models\LivingBeing;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grade extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'living_being_grades';

    public function livingBeings(){
        return $this->hasMany(LivingBeing::class, 'living_being_grade_id', 'id');
    }
}
