<?php

namespace App\Http\Controllers\API\App\Account;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\App\Accounts\Characters\CharacterResource as CharactersCharacterResource;
use App\Http\Resources\API\App\Characters\CharacterResource;
use App\Models\Account\Character as AccountCharacter;
use App\Models\Character as Character;
use App\Models\User;
use Illuminate\Http\Request;
use App\Exceptions\API\App\Accounts\Characters\CharacterDoNotBelongsToActualAccountException;

class CharactersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::find(auth()->user()->id);
        $user->load(['accounts']);
        $actual_account = $user->accounts()->first();
        $list = $actual_account->accountCharacters;
        $list->load(['character.characterIcon']);

        return [
            'list' => CharactersCharacterResource::collection($list)
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $characters_list = Character::where('released','=',1)->get();
        $characters_list->load(['characterIcon']);

        $model = new AccountCharacter();
        return [
            'form' => [
                'characters' => CharacterResource::collection($characters_list)
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
        $model->account_weapon_id = 0;
        $model->level = $request->input('level');
        $model->constellation_level = $request->input('constellation_level');
        $model->basic_talent_level = 1;
        $model->elemental_talent_level = 1;
        $model->burst_talent_level = 1;
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

        return new CharactersCharacterResource($model);
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
        
        $model->load(['character.characterIcon']);

        $characters_list = Character::where('released','=',1)->get();
        $characters_list->load(['characterIcon']);


        return [
            'form' => [
                'characters' => CharacterResource::collection($characters_list)
            ],
            'model' => new CharactersCharacterResource($model)
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
        $model->account_weapon_id = 0;
        $model->level = $request->input('level');
        $model->constellation_level = $request->input('constellation_level');
        $model->basic_talent_level = 1;
        $model->elemental_talent_level = 1;
        $model->burst_talent_level = 1;
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

        return new CharactersCharacterResource($model);
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
        return new CharactersCharacterResource($model);
    }
}
