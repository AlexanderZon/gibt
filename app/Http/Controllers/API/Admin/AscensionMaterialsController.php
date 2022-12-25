<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\Admin\AscensionMaterials\AscensionMaterialResource;
use App\Models\AscensionMaterial;
use App\Models\AscensionMaterial\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AscensionMaterialsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ascension_materials = AscensionMaterial::orderBy('order', 'DESC')->get();

        $ascension_materials->load(['ascensionMaterialTypes']);

        return [
            'data' => AscensionMaterialResource::collection($ascension_materials),
            'form' => [
                'ascension_material_types' => Type::orderBy('order', 'ASC')->get()
            ]
        ];
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
        $ascension_material = new AscensionMaterial();
        $ascension_material->name = $request->input('name');
        $ascension_material->rarity = $request->input('rarity');
        $ascension_material->description = $request->input('description');

        self::refreshModelOrders();
        $total_ascension_materials = AscensionMaterial::all()->count();
        $ascension_material->order = $total_ascension_materials+1;
        $ascension_material->save();

        $ascension_material->ascensionMaterialTypes()->sync($request->input('ascension_material_typeables'));

        $ascension_material->load(['ascensionMaterialTypes']);

        return new AscensionMaterialResource($ascension_material);
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
        $ascension_material = AscensionMaterial::find($id);
        $ascension_material->name = $request->input('name');
        $ascension_material->rarity = $request->input('rarity');
        $ascension_material->description = $request->input('description');

        $ascension_material->save();

        $ascension_material->ascensionMaterialTypes()->sync($request->input('ascension_material_typeables'));

        $ascension_material->load(['ascensionMaterialTypes']);

        return new AscensionMaterialResource($ascension_material);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ascension_material = AscensionMaterial::find($id);        
        $ascension_material->delete();

        return new AscensionMaterialResource($ascension_material);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function picture(Request $request, $id)
    {        
        $path = Storage::put('ascension_materials/icons', $request->file('file'));

        $ascension_material = AscensionMaterial::find($id);
        $ascension_material->icon = $path;
        $ascension_material->save();

        self::refreshModelOrders();

        $ascension_material->load(['ascensionMaterialTypes']);

        return new AscensionMaterialResource($ascension_material);
    }


    public static function refreshModelOrders(){
        $ascension_materials = AscensionMaterial::orderBy('order', 'ASC')->get();

        $counter = 0;
        foreach($ascension_materials as $ascension_material){
            $ascension_material->order = $counter++;
            $ascension_material->save();
        }
    }
}
