<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\Admin\WeaponTypes\WeaponTypeResource;
use App\Models\Weapon\Type as WeaponType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WeaponTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $weapon_types = WeaponType::all();

        return WeaponTypeResource::collection($weapon_types);
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
        $weapon_type = new WeaponType();
        $weapon_type->name = $request->input('name');

        self::refreshModelOrders();
        $total_weapon_types = WeaponType::all()->count();
        $weapon_type->order = $total_weapon_types+1;
        $weapon_type->save();

        return new WeaponTypeResource($weapon_type);
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
        $weapon_type = WeaponType::find($id);
        $weapon_type->name = $request->input('name');

        $weapon_type->save();

        return new WeaponTypeResource($weapon_type);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $weapon_type = WeaponType::find($id);        
        $weapon_type->delete();

        return new WeaponTypeResource($weapon_type);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function picture(Request $request, $id)
    {        
        $path = Storage::put('weapon-types/icons', $request->file('file'));

        $weapon_type = WeaponType::find($id);
        $weapon_type->icon = $path;
        $weapon_type->save();

        self::refreshModelOrders();

        return new WeaponTypeResource($weapon_type);
    }

    public static function refreshModelOrders(){
        $weapon_types = WeaponType::orderBy('order', 'ASC')->get();

        $counter = 0;
        foreach($weapon_types as $weapon_type){
            $weapon_type->order = $counter++;
            $weapon_type->save();
        }
    }
}
