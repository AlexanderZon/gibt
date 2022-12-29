<?php

namespace App\Http\Controllers\API\Crawler\Characters;

use App\Http\Controllers\Controller;
use App\Models\AscensionMaterial;
use App\Models\Association;
use App\Models\Character;
use App\Models\Character\Image;
use App\Models\Character\Skill\Ascension as CharacterSkillAscension;
use App\Models\Character\Stat as CharacterStat;
use App\Models\Element;
use App\Models\Stat\Type as StatType;
use App\Models\Vision;
use App\Models\Weapon\Type as WeaponType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use \Illuminate\Support\Str;

class CharactersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
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
        $response = self::setCharacterData($request);
        if($response['errors']->count() > 0) return response(['errors' => $response['errors']->toArray()], 500);
        else {
            $response = self::setCharacterData($request, true);
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

    public function setCharacterData(Request $request, $save = false)
    {
        $errors = collect([]);
        $character = Character::where('name','=',$request->name)->first();
        if($character == null){
            $character = new Character();
        }
        $character->name = $request->input('name');
        $character->title = $request->input('title');
        $character->rarity = $request->input('rarity');
        $character->occupation = $request->input('occupation');
        $element = Element::where('name','=',$request->input('element'))->first();
        if($element == null) $errors->push('Element "'.$request->input('element').'" not found');
        else $character->element_id = $element->id;
        $vision = Vision::where('name','=',$request->input('vision'))->first();
        if($vision == null) $errors->push('Vision "'.$request->input('vision').'" not found');
        else $character->vision_id = $vision->id;
        $weapon_type = WeaponType::where('name','=',$request->input('weapon_type'))->first();
        if($weapon_type == null) $errors->push('Weapon Type "'.$request->input('weapon_type').'" not found');
        else $character->weapon_type_id = $weapon_type->id;
        $association = Association::where('name','=',$request->input('association'))->first();
        if($association == null) $errors->push('Association "'.$request->input('association').'" not found');
        else $character->association_id = $association->id;
        $character->day_of_birth = $request->input('day_of_birth');
        $character->month_of_birth = $request->input('month_of_birth');
        $character->constellation = $request->input('constellation');
        $character->description = $request->input('description');
        $character->released = false;

        $ascension_materials = collect([]);
        foreach($request->input('ascension_materials') as $ascension_material_name){
            $ascension_material = AscensionMaterial::where('name','=',$ascension_material_name)->first();
            if($ascension_material == null) $errors->push('Ascension Material "'.$ascension_material_name.'" not found');
            else $ascension_materials->push($ascension_material->id);
        }

        $skill_ascension_materials = collect([]);
        foreach($request->input('skill_ascension_materials') as $skill_ascension_material_name){
            $skill_ascension_material = AscensionMaterial::where('name','=',$skill_ascension_material_name)->first();
            if($skill_ascension_material == null) $errors->push('Skill Ascension Material "'.$skill_ascension_material_name.'" not found');
            else $skill_ascension_materials->push($skill_ascension_material->id);
        }

        $character_stats = collect([]);
        foreach($request->input('character_stats') as $character_stat_data){
            $character_stat = new CharacterStat();
            $character_stat->level = $character_stat_data['level'];
            $character_stat->hp = $character_stat_data['hp'];
            $character_stat->atk = $character_stat_data['atk'];
            $character_stat->def = $character_stat_data['def'];
            $character_stat->crit_rate = $character_stat_data['crit_rate'];
            $character_stat->crit_dmg = $character_stat_data['crit_dmg'];
            
            $variable_stat = StatType::where('code','=',$character_stat_data['variable_stat'])->first();
            if($variable_stat == null) $errors->push('Stat Type "'.$character_stat_data['variable_stat'].'" not found');
            else $character_stat->variable_stat_id = $variable_stat->id;
            $character_stat->variable_stat_value = $character_stat_data['variable_stat_value'];
            
            $character_stat->char_jewel_id = 0;
            $character_stat->char_jewel_quantity = 0;
            $character_stat->char_elemental_stone_id = 0;
            $character_stat->char_elemental_stone_quantity = 0;
            $character_stat->char_local_material_id = 0;
            $character_stat->char_local_material_quantity = 0;
            $character_stat->char_common_item_id = 0;
            $character_stat->char_common_item_quantity = 0;
            $character_stat->mora_quantity = 0;

            foreach($character_stat_data['materials'] as $character_stat_material_data){
                if($character_stat_material_data['name'] != 'Mora'){
                    $character_stat_material = AscensionMaterial::where('name','=',$character_stat_material_data['name'])->first();
                    if($character_stat_material == null) $errors->push('Character Stat Ascension Material "'.$character_stat_material_data['name'].'" not found');
                    else {
                        $character_stat_material->load(['ascensionMaterialTypes']);
                        if($character_stat_material->ascensionMaterialTypes->some(function($value){
                            return $value->name == "Jewels";
                        })){
                            $character_stat->char_jewel_id = $character_stat_material->id;
                            $character_stat->char_jewel_quantity = $character_stat_material_data['quantity'];
                        }
                        if($character_stat_material->ascensionMaterialTypes->some(function($value){
                            return $value->name == "Elemental Stones";
                        })){
                            $character_stat->char_elemental_stone_id = $character_stat_material->id;
                            $character_stat->char_elemental_stone_quantity = $character_stat_material_data['quantity'];
                        }
                        if($character_stat_material->ascensionMaterialTypes->some(function($value){
                            return $value->name == "Local Materials";
                        })){
                            $character_stat->char_local_material_id = $character_stat_material->id;
                            $character_stat->char_local_material_quantity = $character_stat_material_data['quantity'];
                        }
                        if($character_stat_material->ascensionMaterialTypes->some(function($value){
                            return $value->name == "Common Materials";
                        })){
                            $character_stat->char_common_item_id = $character_stat_material->id;
                            $character_stat->char_common_item_quantity = $character_stat_material_data['quantity'];
                        }
                    }
                } else {
                    $character_stat->mora_quantity = $character_stat_material_data['quantity'];
                }
            }
            $character_stats->push($character_stat);
        }

        $character_skills = collect([]);
        foreach($request->input('character_skills') as $character_skill_data){
            $character_skill_ascension = new CharacterSkillAscension();
            $character_skill_ascension->level = $character_skill_data['level'];
            $character_skill_ascension->talent_book_id = 0;
            $character_skill_ascension->talent_book_quantity = 0;
            $character_skill_ascension->talent_boss_item_id = 0;
            $character_skill_ascension->talent_boss_item_quantity = 0;
            $character_skill_ascension->reward_item_id = 0;
            $character_skill_ascension->reward_item_quantity = 0;
            $character_skill_ascension->char_common_item_id = 0;
            $character_skill_ascension->char_common_item_quantity = 0;
            $character_skill_ascension->mora_quantity = 0;
            foreach($character_skill_data['materials'] as $character_skill_material_data){
                if($character_skill_material_data['name'] != 'Mora'){
                    $character_skill_material = AscensionMaterial::where('name','=',$character_skill_material_data['name'])->first();
                    if($character_skill_material == null) $errors->push('Character Skill Ascension Material "'.$character_skill_material_data['name'].'" not found');
                    else {
                        $character_skill_material->load(['ascensionMaterialTypes']);
                        if($character_skill_material->ascensionMaterialTypes->some(function($value){
                            return $value->name == "Book Materials";
                        })){
                            $character_skill_ascension->talent_book_id = $character_skill_material->id;
                            $character_skill_ascension->talent_book_quantity = $character_skill_material_data['quantity'];
                        }
                        else if($character_skill_material->ascensionMaterialTypes->some(function($value){
                            return $value->name == "Boss Materials";
                        })){
                            $character_skill_ascension->talent_boss_item_id = $character_skill_material->id;
                            $character_skill_ascension->talent_boss_item_quantity = $character_skill_material_data['quantity'];
                        }
                        else if($character_skill_material->ascensionMaterialTypes->some(function($value){
                            return $value->name == "Reward Materials";
                        })){
                            $character_skill_ascension->reward_item_id = $character_skill_material->id;
                            $character_skill_ascension->reward_item_quantity = $character_skill_material_data['quantity'];
                        }
                        else if($character_skill_material->ascensionMaterialTypes->some(function($value){
                            return $value->name == "Common Materials";
                        })){
                            $character_skill_ascension->char_common_item_id = $character_skill_material->id;
                            $character_skill_ascension->char_common_item_quantity = $character_skill_material_data['quantity'];
                        }
                    }
                } else {
                    $character_skill_ascension->mora_quantity = $character_skill_material_data['quantity'];
                }
            }
            $character_skills->push($character_skill_ascension);
        }
        if($save) {
            $character->save();
            $character->ascensionMaterials()->sync($ascension_materials);
            $character->skillAscensionMaterials()->sync($skill_ascension_materials);

            $character->characterStats()->delete();
            foreach($character_stats as $character_stat){
                $character_stat->character_id = $character->id;
                $character_stat->save();
            }
            $character->characterSkillAscensions()->delete();
            foreach($character_skills as $character_skill){
                $character_skill->character_id = $character->id;
                $character_skill->save();
            }

            foreach($request->input('gallery') as $character_image){
                $file = file_get_contents($character_image['url']);
                $name = Str::slug($character->name).'_'.$character_image['type'].'.webp';
                $path = 'characters/gallery/'.$name;
                $is_saved = Storage::put($path, $file);
                if($is_saved){
                    $character_icon = null;
                    switch($character_image['type']){
                        case 'icon': 
                            $character_icon = $character->characterIcon;
                            break;
                        case 'side_icon': 
                            $character_icon = $character->characterSideIcon;
                            break;
                        case 'gacha_card': 
                            $character_icon = $character->characterGachaCard;
                            break;
                        case 'gacha_splash': 
                            $character_icon = $character->characterGachaSplash;
                            break;
                    }
                    if($character_icon == null){
                        $character_icon = new Image();
                        $character_icon->character_id = $character->id;
                        $character_icon->type = $character_image['type'];
                    }
                    $character_icon->url = $path;
                    $character_icon->save();    
                }
            }
        }

        return [
            'errors' => $errors,
        ];
    }
}
