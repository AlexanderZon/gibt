<?php

namespace App\Http\Controllers\API\Admin\Characters\Skills;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\Admin\AscensionMaterials\AscensionMaterialResource;
use App\Http\Resources\API\Admin\Characters\Skills\SkillResource as CharacterSkillResource;
use App\Models\Character;
use App\Models\Character\Skill\Ascension as SkillAscension;
use Illuminate\Http\Request;

class SkillsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $character_id)
    {
        $character = Character::find($character_id);
        $character_skill = new SkillAscension();

        $character->load(['skillAscensionMaterials.ascensionMaterialTypes', 'ascensionMaterials.ascensionMaterialTypes']);

        return [
            'model' => $character_skill,
            'form' => [
                'ascension_materials' => AscensionMaterialResource::collection($character->ascensionMaterials),
                'skill_ascension_materials' => AscensionMaterialResource::collection($character->skillAscensionMaterials)
            ]
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $character_id)
    {
        $character = Character::find($character_id);
        $character_skill = new SkillAscension();
        $character_skill->character_id = $character->id;
        $character_skill->level = $request->input('level');
        $character_skill->talent_book_item_id = $request->input('talent_book_item.id');
        $character_skill->talent_book_item_quantity = $request->input('talent_book_item_quantity');
        $character_skill->talent_boss_item_id = $request->input('talent_boss_item.id');
        $character_skill->talent_boss_item_quantity = $request->input('talent_boss_item_quantity');
        $character_skill->reward_item_id = $request->input('reward_item.id');
        $character_skill->reward_item_quantity = $request->input('reward_item_quantity');
        $character_skill->char_common_item_id = $request->input('char_common_item.id');
        $character_skill->char_common_item_quantity = $request->input('char_common_item_quantity');
        $character_skill->mora_quantity = $request->input('mora_quantity');
        $character_skill->save();

        $character_skill->load(['talentBookItem', 'talentBossItem', 'rewardItem', 'charCommonItem']);

        return new CharacterSkillResource($character_skill);
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $character_id, $id)
    {
        $character = Character::find($character_id);
        $character_skill = SkillAscension::find($id);
        $character_skill->level = $request->input('level');
        $character_skill->hp = $request->input('hp');
        $character_skill->atk = $request->input('atk');
        $character_skill->def = $request->input('def');
        $character_skill->crit_rate = $request->input('crit_rate');
        $character_skill->crit_dmg = $request->input('crit_dmg');
        $character_skill->variable_stat_id = $request->input('variable_stat.id');
        $character_skill->variable_stat_value = $request->input('variable_stat_value');
        $character_skill->char_jewel_id = $request->input('char_jewel.id');
        $character_skill->char_jewel_quantity = $request->input('char_jewel_quantity');
        $character_skill->char_elemental_stone_id = $request->input('char_elemental_stone.id');
        $character_skill->char_elemental_stone_quantity = $request->input('char_elemental_stone_quantity');
        $character_skill->char_local_material_id = $request->input('char_local_material.id');
        $character_skill->char_local_material_quantity = $request->input('char_local_material_quantity');
        $character_skill->char_common_item_id = $request->input('char_common_item.id');
        $character_skill->char_common_item_quantity = $request->input('char_common_item_quantity');
        $character_skill->mora_quantity = $request->input('mora_quantity');
        $character_skill->save();

        $character_skill->load(['talentBookItem', 'talentBossItem', 'rewardItem', 'charCommonItem']);

        return new CharacterSkillResource($character_skill);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
