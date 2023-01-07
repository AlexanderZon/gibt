<?php

namespace App\Http\Controllers\API\Crawler\Weapons;

use App\Http\Controllers\Controller;
use App\Models\AscensionMaterial;
use App\Models\Stat\Type as StatType;
use App\Models\Weapon;
use App\Models\Weapon\Image;
use App\Models\Weapon\Stat as WeaponStat;
use App\Models\Weapon\Type as WeaponType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use \Illuminate\Support\Str;

class WeaponsController extends Controller
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $response = self::setWeaponData($request);
        if($response['errors']->count() > 0) return response(['errors' => $response['errors']->toArray()], 500);
        else {
            $response = self::setWeaponData($request, true);
            return response('ok', 200);
        }
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

    public function setWeaponData(Request $request, $save = false)
    {
        $errors = collect([]);
        $weapon = Weapon::where('name','=',$request->name)->first();
        if($weapon == null){
            $weapon = new Weapon();
        }
        $weapon->name = $request->input('name');
        $weapon->rarity = $request->input('rarity');
        $weapon_type = WeaponType::where('name','=',$request->input('weapon_type'))->first();
        if($weapon_type == null) $errors->push('Weapon Type "'.$request->input('weapon_type').'" not found');
        else $weapon->weapon_type_id = $weapon_type->id;
        $weapon->description = $request->input('description');
        $weapon->released = false;

        $ascension_materials = collect([]);
        foreach($request->input('ascension_materials') as $ascension_material_name){
            $ascension_material = AscensionMaterial::where('name','=',$ascension_material_name)->first();
            if($ascension_material == null) $errors->push('Ascension Material "'.$ascension_material_name.'" not found');
            else $ascension_materials->push($ascension_material->id);
        }

        $weapon_stats = collect([]);
        foreach($request->input('weapon_stats') as $weapon_stat_data){
            $weapon_stat = new WeaponStat();
            $weapon_stat->level = $weapon_stat_data['level'];
            $weapon_stat->atk = $weapon_stat_data['atk'];
            
            $weapon_stat->variable_stat_id = 0;
            $weapon_stat->variable_stat_value = 0;
            $weapon_stat->weap_primary_material_id = 0;
            $weapon_stat->weap_primary_material_quantity = 0;
            $weapon_stat->weap_secondary_material_id = 0;
            $weapon_stat->weap_secondary_material_quantity = 0;
            $weapon_stat->weap_common_item_id = 0;
            $weapon_stat->weap_common_item_quantity = 0;
            $weapon_stat->mora_quantity = 0;
            
            if($weapon->rarity > 2){
                $variable_stat = StatType::where('code','=',$weapon_stat_data['variable_stat'])->first();
                if($variable_stat == null) $errors->push('Stat Type "'.$weapon_stat_data['variable_stat'].'" not found');
                else $weapon_stat->variable_stat_id = $variable_stat->id;
                $weapon_stat->variable_stat_value = $weapon_stat_data['variable_stat_value'];
            }

            foreach($weapon_stat_data['materials'] as $weapon_stat_material_data){
                if($weapon_stat_material_data['name'] != 'Mora'){
                    $weapon_stat_material = AscensionMaterial::where('name','=',$weapon_stat_material_data['name'])->first();
                    if($weapon_stat_material == null) $errors->push('Weapon Stat Ascension Material "'.$weapon_stat_material_data['name'].'" not found');
                    else {
                        $weapon_stat_material->load(['ascensionMaterialTypes']);
                        if($weapon_stat_material->ascensionMaterialTypes->some(function($value){
                            return $value->name == "Weapon Ascension Materials (Primary)";
                        })){
                            $weapon_stat->weap_primary_material_id = $weapon_stat_material->id;
                            $weapon_stat->weap_primary_material_quantity = $weapon_stat_material_data['quantity'];
                        }
                        if($weapon_stat_material->ascensionMaterialTypes->some(function($value){
                            return $value->name == "Weapon Ascension Materials (Secondary)";
                        })){
                            $weapon_stat->weap_secondary_material_id = $weapon_stat_material->id;
                            $weapon_stat->weap_secondary_material_quantity = $weapon_stat_material_data['quantity'];
                        }
                        if($weapon_stat_material->ascensionMaterialTypes->some(function($value){
                            return $value->name == "Common Materials";
                        })){
                            $weapon_stat->weap_common_item_id = $weapon_stat_material->id;
                            $weapon_stat->weap_common_item_quantity = $weapon_stat_material_data['quantity'];
                        }
                    }
                } else {
                    $weapon_stat->mora_quantity = $weapon_stat_material_data['quantity'];
                }
            }
            $weapon_stats->push($weapon_stat);
        }
        if($save) {
            $weapon->save();
            $weapon->ascensionMaterials()->sync($ascension_materials->sortDesc());

            $weapon->weaponStats()->delete();
            foreach($weapon_stats as $weapon_stat){
                $weapon_stat->weapon_id = $weapon->id;
                $weapon_stat->save();
            }

            foreach($request->input('gallery') as $weapon_image){
                $file = file_get_contents($weapon_image['url']);
                $name = Str::slug($weapon->name).'_'.$weapon_image['type'].'.webp';
                $path = 'weapons/gallery/'.$name;
                $is_saved = Storage::put($path, $file);
                if($is_saved){
                    $weapon_icon = null;
                    switch($weapon_image['type']){
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
                        $weapon_icon->type = $weapon_image['type'];
                    }
                    $weapon_icon->url = $path;
                    $weapon_icon->save();    
                }
            }
        }

        return [
            'errors' => $errors,
        ];
    }
}
