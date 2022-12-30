<?php

namespace App\Http\Controllers\API\Admin\Weapons;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\Admin\AscensionMaterials\AscensionMaterialResource;
use App\Http\Resources\API\Admin\Weapons\WeaponResource;
use App\Http\Resources\API\Admin\WeaponTypes\WeaponTypeResource;
use App\Models\AscensionMaterial;
use App\Models\Weapon;
use App\Models\Weapon\Image;
use App\Models\Weapon\Type as WeaponType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WeaponsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $weapons = Weapon::all();

        $weapons->load(['weaponType', 'ascensionMaterials', 'weaponIcon']);

        return [
            'data' => WeaponResource::collection($weapons),
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $weapon = new Weapon();

        return [
            'model' => $weapon,
            'form' => self::getFormData()
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
        $weapon = new Weapon();
        $weapon->name = $request->input('name');
        $weapon->rarity = $request->input('rarity');
        $weapon->weapon_type_id = $request->input('weapon_type.id');
        $weapon->description = $request->input('description');
        $weapon->released = $request->input('released');
        $weapon->save();
        
        $weapon->ascensionMaterials()->sync($request->input('ascension_materialables'));

        return new WeaponResource($weapon);
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
        $weapon = Weapon::find($id);

        $weapon->load([
            'weaponType', 
            'ascensionMaterials',  
            'weaponIcon', 
            'weaponAwakenedIcon', 
            'weaponGachaCard', 
            'weaponStats.variableStat',
            'weaponStats.weapPrimaryMaterial',
            'weaponStats.weapSecondaryMaterial',
            'weaponStats.weapCommonItem',
        ]);

        return [
            'model' => new WeaponResource($weapon),
            'form' => self::getFormData()
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function picture(Request $request, $id)
    {        
        $weapon = Weapon::find($id);
        
        $path = Storage::put('weapons/gallery', $request->file('file'));

        $weapon_icon = null;
        switch($request->type){
            case 'icon': 
                $weapon_icon = $weapon->weaponIcon;
                break;
            case 'awakened_icon': 
                $weapon_icon = $weapon->weaponAwakenedIcon;
                break;
            case 'gacha_card': 
                $weapon_icon = $weapon->weaponGachaCard;
                break;
        }
        if($weapon_icon == null){
            $weapon_icon = new Image();
            $weapon_icon->weapon_id = $weapon->id;
            $weapon_icon->type = $request->type;
        }
        $weapon_icon->url = $path;
        $weapon_icon->save();

        $weapon->load(['weaponType', 'ascensionMaterials', 'weaponIcon', 'weaponAwakenedIcon', 'weaponGachaCard']);

        return new WeaponResource($weapon);
    }

    public static function getFormData(){
        $ascension_materials = AscensionMaterial::orderBy('order', 'ASC')->get();

        $ascension_materials->load(['ascensionMaterialTypes']);

        return [
            'ascension_materials' => AscensionMaterialResource::collection($ascension_materials),
            'weapon_types' => WeaponTypeResource::collection(WeaponType::orderBy('order', 'ASC')->get()),
        ];
    }
}
