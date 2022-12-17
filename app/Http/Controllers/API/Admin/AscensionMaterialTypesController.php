<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\Admin\AscensionMaterialTypes\AscensionMaterialTypeResource;
use App\Models\AscensionMaterial\Type as AscensionMaterialType;
use Illuminate\Http\Request;

class AscensionMaterialTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ascension_material_types = AscensionMaterialType::all();

        return AscensionMaterialTypeResource::collection($ascension_material_types);
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
        $ascension_material_type = new AscensionMaterialType();
        $ascension_material_type->name = $request->input('name');

        self::refreshModelOrders();
        $total_ascension_material_types = AscensionMaterialType::all()->count();
        $ascension_material_type->order = $total_ascension_material_types+1;
        $ascension_material_type->save();

        return new AscensionMaterialTypeResource($ascension_material_type);
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
        $ascension_material_type = AscensionMaterialType::find($id);
        $ascension_material_type->name = $request->input('name');

        $ascension_material_type->save();

        return new AscensionMaterialTypeResource($ascension_material_type);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ascension_material_type = AscensionMaterialType::find($id);        
        $ascension_material_type->delete();

        return new AscensionMaterialTypeResource($ascension_material_type);
    }

    public static function refreshModelOrders(){
        $ascension_material_types = AscensionMaterialType::orderBy('order', 'ASC')->get();

        $counter = 0;
        foreach($ascension_material_types as $ascension_material_type){
            $ascension_material_type->order = $counter++;
            $ascension_material_type->save();
        }
    }
}
