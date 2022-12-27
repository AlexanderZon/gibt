<?php

namespace App\Http\Controllers\API\Admin\Characters\Stats;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\Admin\AscensionMaterials\AscensionMaterialResource;
use App\Http\Resources\API\Admin\Characters\Stats\StatResource as CharacterStatResource;
use App\Http\Resources\API\Admin\StatTypes\StatTypeResource;
use App\Models\Character;
use App\Models\Character\Stat;
use App\Models\Stat\Type as StatType;
use Illuminate\Http\Request;

class StatsController extends Controller
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
        $character_stat = new Stat();

        $character->load(['ascensionMaterials.ascensionMaterialTypes']);

        return [
            'model' => $character_stat,
            'form' => [
                'stat_types' => StatTypeResource::collection(StatType::orderBy('order', 'ASC')->get()),
                'ascension_materials' => AscensionMaterialResource::collection($character->ascensionMaterials)
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
        $character_stat = new Stat();
        $character_stat->character_id = $character->id;
        $character_stat->level = $request->input('level');
        $character_stat->hp = $request->input('hp');
        $character_stat->atk = $request->input('atk');
        $character_stat->def = $request->input('def');
        $character_stat->crit_rate = $request->input('crit_rate');
        $character_stat->crit_dmg = $request->input('crit_dmg');
        $character_stat->variable_stat_id = $request->input('variable_stat.id');
        $character_stat->variable_stat_value = $request->input('variable_stat_value');
        $character_stat->char_jewel_id = $request->input('char_jewel.id');
        $character_stat->char_jewel_quantity = $request->input('char_jewel_quantity');
        $character_stat->char_elemental_stone_id = $request->input('char_elemental_stone.id');
        $character_stat->char_elemental_stone_quantity = $request->input('char_elemental_stone_quantity');
        $character_stat->char_local_material_id = $request->input('char_local_material.id');
        $character_stat->char_local_material_quantity = $request->input('char_local_material_quantity');
        $character_stat->char_common_item_id = $request->input('char_common_item.id');
        $character_stat->char_common_item_quantity = $request->input('char_common_item_quantity');
        $character_stat->mora_quantity = $request->input('mora_quantity');
        $character_stat->save();

        $character_stat->load(['variableStat', 'charJewel', 'charElementalStone', 'charLocalMaterial', 'charCommonItem']);

        return new CharacterStatResource($character_stat);
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
        $character_stat = Stat::find($id);
        $character_stat->level = $request->input('level');
        $character_stat->hp = $request->input('hp');
        $character_stat->atk = $request->input('atk');
        $character_stat->def = $request->input('def');
        $character_stat->crit_rate = $request->input('crit_rate');
        $character_stat->crit_dmg = $request->input('crit_dmg');
        $character_stat->variable_stat_id = $request->input('variable_stat.id');
        $character_stat->variable_stat_value = $request->input('variable_stat_value');
        $character_stat->char_jewel_id = $request->input('char_jewel.id');
        $character_stat->char_jewel_quantity = $request->input('char_jewel_quantity');
        $character_stat->char_elemental_stone_id = $request->input('char_elemental_stone.id');
        $character_stat->char_elemental_stone_quantity = $request->input('char_elemental_stone_quantity');
        $character_stat->char_local_material_id = $request->input('char_local_material.id');
        $character_stat->char_local_material_quantity = $request->input('char_local_material_quantity');
        $character_stat->char_common_item_id = $request->input('char_common_item.id');
        $character_stat->char_common_item_quantity = $request->input('char_common_item_quantity');
        $character_stat->mora_quantity = $request->input('mora_quantity');
        $character_stat->save();

        $character_stat->load(['variableStat', 'charJewel', 'charElementalStone', 'charLocalMaterial', 'charCommonItem']);

        return new CharacterStatResource($character_stat);
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
