<?php

namespace App\Models\AscensionMaterial;

use App\Models\AscensionMaterial;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FarmingDay extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ascension_material_farming_days';

    public function ascensionMaterials(){
        return $this->belongsTo(AscensionMaterial::class, 'ascension_material_id', 'id');
    }
}
