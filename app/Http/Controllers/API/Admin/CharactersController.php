<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\Admin\AscensionMaterials\AscensionMaterialResource;
use App\Http\Resources\API\Admin\Characters\CharacterResource;
use App\Http\Resources\API\Admin\Elements\ElementResource;
use App\Http\Resources\API\Admin\Visions\VisionResource;
use App\Http\Resources\API\Admin\WeaponTypes\WeaponTypeResource;
use App\Models\AscensionMaterial;
use App\Models\Association;
use App\Models\Character;
use App\Models\Element;
use App\Models\Vision;
use App\Models\Weapon\Type as WeaponType;
use Illuminate\Http\Request;

class CharactersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $characters = Character::all();

        $characters->load(['element', 'vision', 'weaponType', 'ascensionMaterials']);

        return [
            'data' => CharacterResource::collection($characters),
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $character = new Character();

        return [
            'model' => $character,
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
        $character = new Character();
        $character->name = $request->input('name');
        $character->title = $request->input('title');
        $character->rarity = $request->input('rarity');
        $character->occupation = $request->input('occupation');
        $character->element_id = $request->input('element.id');
        $character->vision_id = $request->input('vision.id');
        $character->weapon_type_id = $request->input('weapon_type.id');
        $character->association_id = $request->input('association.id');
        $character->day_of_birth = $request->input('day_of_birth');
        $character->month_of_birth = $request->input('month_of_birth');
        $character->constellation = $request->input('constellation');
        $character->description = $request->input('description');
        $character->released = $request->input('released');
        $character->save();
        
        $character->ascensionMaterials()->sync($request->input('ascension_materialables'));
        $character->skillAscensionMaterials()->sync($request->input('skill_ascension_materialables'));

        return new CharacterResource($character);
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
        $character = Character::find($id);

        $character->load(['element', 'vision', 'weaponType', 'association', 'ascensionMaterials', 'skillAscensionMaterials']);

        return [
            'model' => new CharacterResource($character),
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
        $character = Character::find($id);
        $character->name = $request->input('name');
        $character->title = $request->input('title');
        $character->rarity = $request->input('rarity');
        $character->occupation = $request->input('occupation');
        $character->element_id = $request->input('element.id');
        $character->vision_id = $request->input('vision.id');
        $character->weapon_type_id = $request->input('weapon_type.id');
        $character->association_id = $request->input('association.id');
        $character->day_of_birth = $request->input('day_of_birth');
        $character->month_of_birth = $request->input('month_of_birth');
        $character->constellation = $request->input('constellation');
        $character->description = $request->input('description');
        $character->released = $request->input('released');
        $character->save();
        
        $character->ascensionMaterials()->sync($request->input('ascension_materialables'));
        $character->skillAscensionMaterials()->sync($request->input('skill_ascension_materialables'));

        return new CharacterResource($character);
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

    public static function getFormData(){
        $ascension_materials = AscensionMaterial::orderBy('order', 'ASC')->get();

        $ascension_materials->load(['ascensionMaterialTypes']);

        return [
            'elements' => ElementResource::collection(Element::orderBy('order', 'ASC')->get()),
            'visions' => VisionResource::collection(Vision::orderBy('order', 'ASC')->get()),
            'associations' => Association::orderBy('order', 'ASC')->get(),
            'ascension_materials' => AscensionMaterialResource::collection($ascension_materials),
            'weapon_types' => WeaponTypeResource::collection(WeaponType::orderBy('order', 'ASC')->get()),
        ];
    }
}
