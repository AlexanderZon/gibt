<?php

namespace App\Http\Controllers\API\Admin\Weapons\Stats;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\Admin\AscensionMaterials\AscensionMaterialResource;
use App\Http\Resources\API\Admin\StatTypes\StatTypeResource;
use App\Http\Resources\API\Admin\Weapons\Stats\StatResource as WeaponStatResource;
use App\Models\Stat\Type as StatType;
use App\Models\Weapon;
use App\Models\Weapon\Stat;
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
    public function create(Request $request, $weapon_id)
    {
        $weapon = Weapon::find($weapon_id);
        $weapon_stat = new Stat();

        $weapon->load(['ascensionMaterials.ascensionMaterialTypes']);

        return [
            'model' => $weapon_stat,
            'form' => [
                'stat_types' => StatTypeResource::collection(StatType::orderBy('order', 'ASC')->get()),
                'ascension_materials' => AscensionMaterialResource::collection($weapon->ascensionMaterials)
            ]
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $weapon_id)
    {
        $weapon = Weapon::find($weapon_id);
        $weapon_stat = new Stat();
        $weapon_stat->weapon_id = $weapon->id;
        $weapon_stat->level = $request->input('level');
        $weapon_stat->atk = $request->input('atk');
        $weapon_stat->variable_stat_id = $request->input('variable_stat.id');
        $weapon_stat->variable_stat_value = $request->input('variable_stat_value');
        $weapon_stat->weap_primary_material_id = $request->input('weap_primary_material.id');
        $weapon_stat->weap_primary_material_quantity = $request->input('weap_primary_material_quantity');
        $weapon_stat->weap_secondary_material_id = $request->input('weap_secondary_material.id');
        $weapon_stat->weap_secondary_material_quantity = $request->input('weap_secondary_material_quantity');
        $weapon_stat->weap_common_item_id = $request->input('weap_common_item.id');
        $weapon_stat->weap_common_item_quantity = $request->input('weap_common_item_quantity');
        $weapon_stat->mora_quantity = $request->input('mora_quantity');
        $weapon_stat->save();

        $weapon_stat->load(['variableStat', 'weapPrimaryMaterial', 'weapSecondaryMaterial', 'weapCommonItem']);

        return new WeaponStatResource($weapon_stat);
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
    public function update(Request $request, $id)
    {
        //
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
