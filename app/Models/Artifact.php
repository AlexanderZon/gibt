<?php

namespace App\Models;

use App\Models\Artifact\Set;
use App\Models\Artifact\Type;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Artifact extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'artifacts';

    public function artifactSet(){
        return $this->belongsTo(Set::class, 'artifact_set_id', 'id');
    }

    public function artifactType(){
        return $this->belongsTo(Type::class, 'type', 'code');
    }
}
