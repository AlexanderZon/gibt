<?php

namespace App\Http\Controllers\API\App\Account;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\App\Accounts\Characters\CharacterResource as AccountCharactersCharacterResource;
use App\Http\Resources\API\App\Characters\CharacterResource;
use App\Models\Account\Character as AccountCharacter;
use App\Models\Character as Character;
use App\Models\User;
use Illuminate\Http\Request;
use App\Exceptions\API\App\Accounts\Characters\CharacterDoNotBelongsToActualAccountException;
use App\Http\Resources\API\App\Accounts\Weapons\WeaponResource;
use App\Models\Account\Weapon;

class CharactersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $list = $request->actualAccount->accountCharacters;
        $list = self::loadAccountCharacterData($list);

        return [
            'list' => AccountCharactersCharacterResource::collection($list)
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $account_characters_ids = $request->actualAccount->accountCharacters()->select(['character_id'])->get()->map(function($account_character) { return $account_character->character_id; });

        $characters_list = Character::whereReleased(1)->whereNotIn('id', $account_characters_ids)->get();
        $characters_list->load(['characterIcon']);
        
        $account_characters = $request->actualAccount->accountCharacters;
        $account_characters->load(['accountWeapon']);

        $account_weapons_selected_ids = $account_characters->map(function($account_character) {
            return $account_character->account_weapon_id;
        });
        $account_weapons_list = $request->actualAccount->accountWeapons()->whereNotIn('id', $account_weapons_selected_ids)->get();
        $account_weapons_list->load(['weapon.weaponIcon']);

        $model = new AccountCharacter();
        return [
            'form' => [
                'characters' => CharacterResource::collection($characters_list),
                'account_weapons' => WeaponResource::collection($account_weapons_list)
            ],
            'model' => $model
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = new AccountCharacter();
        $model->account_id = $request->actualAccount->id;
        $model->character_id = $request->input('character.id');
        $model->account_weapon_id = $request->input('account_weapon.id');
        $model->level = $request->input('level');
        $model->constellation_level = $request->input('constellation_level');
        $model->basic_talent_level = $request->input('basic_talent_level');
        $model->elemental_talent_level = $request->input('elemental_talent_level');
        $model->burst_talent_level = $request->input('burst_talent_level');
        $model->friendship_level = 1;
        $model->artf_flower_id = 0;
        $model->artf_flower_level = 0;
        $model->artf_plume_id = 0;
        $model->artf_plume_level = 0;
        $model->artf_sands_id = 0;
        $model->artf_sands_level = 0;
        $model->artf_goblet_id = 0;
        $model->artf_goblet_level = 0;
        $model->artf_circlet_id = 0;
        $model->artf_circlet_level = 0;
        $model->save();

        $model->load(['character.characterIcon']);

        return new AccountCharactersCharacterResource($model);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $model = AccountCharacter::find($id);
        if($model->account_id != $request->actualAccount->id) throw new CharacterDoNotBelongsToActualAccountException();
        
        $model->load(['character.characterIcon', 'accountWeapon.weapon.weaponIcon']);

        $characters_list = Character::where('released','=',1)->get();
        $characters_list->load(['characterIcon']);

        $account_weapons_list = $request->actualAccount->accountWeapons;
        $account_weapons_list->load(['weapon.weaponIcon']);

        return [
            'form' => [
                'characters' => CharacterResource::collection($characters_list),
                'account_weapons' => WeaponResource::collection($account_weapons_list)
            ],
            'model' => new AccountCharactersCharacterResource($model)
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $model = AccountCharacter::find($id);
        if($model->account_id != $request->actualAccount->id) throw new CharacterDoNotBelongsToActualAccountException();

        $model->account_id = $request->actualAccount->id;
        $model->character_id = $request->input('character.id');
        $model->account_weapon_id = $request->input('account_weapon.id');
        $model->level = $request->input('level');
        $model->constellation_level = $request->input('constellation_level');
        $model->basic_talent_level = $request->input('basic_talent_level');
        $model->elemental_talent_level = $request->input('elemental_talent_level');
        $model->burst_talent_level = $request->input('burst_talent_level');
        $model->friendship_level = 1;
        $model->artf_flower_id = 0;
        $model->artf_flower_level = 0;
        $model->artf_plume_id = 0;
        $model->artf_plume_level = 0;
        $model->artf_sands_id = 0;
        $model->artf_sands_level = 0;
        $model->artf_goblet_id = 0;
        $model->artf_goblet_level = 0;
        $model->artf_circlet_id = 0;
        $model->artf_circlet_level = 0;
        $model->save();

        $model->load(['character.characterIcon']);

        return new AccountCharactersCharacterResource($model);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $model = AccountCharacter::find($id);
        if($model->account_id != $request->actualAccount->id) throw new CharacterDoNotBelongsToActualAccountException();

        $model->delete();
        return new AccountCharactersCharacterResource($model);
    }

    public static function loadAccountCharacterData($model)
    {
        $model->load([
            'character.characterIcon',
            'accountWeapon.weapon.weaponIcon',
            'accountCharacterList'
        ]);

        return $model;
    }
}
