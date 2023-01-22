<?php

namespace App\Models\Account;

use App\Models\Account;
use App\Models\Artifact;
use App\Models\Character as ModelsCharacter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Character extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'account_characters';

    public function account(){
        return $this->belongsTo(Account::class, 'account_id', 'id');
    }

    public function character(){
        return $this->belongsTo(ModelsCharacter::class, 'character_id');
    }

    public function accountWeapon(){
        return $this->belongsTo(Weapon::class, 'account_weapon_id', 'id');
    }

    public function artifactFlower(){
        return $this->belongsTo(Artifact::class, 'artf_flower_id', 'id');
    }

    public function artifactPlume(){
        return $this->belongsTo(Artifact::class, 'artf_plume_id', 'id');
    }

    public function artifactSands(){
        return $this->belongsTo(Artifact::class, 'artf_sands_id', 'id');
    }

    public function artifactGoblet(){
        return $this->belongsTo(Artifact::class, 'artf_goblet_id', 'id');
    }

    public function artifactCirclet(){
        return $this->belongsTo(Artifact::class, 'artf_circlet_id', 'id');
    }
}
