<?php

namespace App\Models\Artifact;

use App\Models\Artifact;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Set extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'artifact_sets';

    public function artifacts(){
        return $this->hasMany(Artifact::class, 'artifact_set_id', 'id');
    }
}
