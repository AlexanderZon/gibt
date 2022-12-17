<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\Admin\StatTypes\StatTypeResource;
use Illuminate\Http\Request;
use App\Models\Stat\Type as StatType;
use Illuminate\Support\Facades\Storage;

class StatTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stat_types = StatType::all();

        return StatTypeResource::collection($stat_types);
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
        $stat_type = new StatType();
        $stat_type->name = $request->input('name');
        $stat_type->code = $request->input('code');

        self::refreshModelOrders();
        $total_stat_types = StatType::all()->count();
        $stat_type->order = $total_stat_types+1;
        $stat_type->save();

        return new StatTypeResource($stat_type);
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
        $stat_type = StatType::find($id);
        $stat_type->name = $request->input('name');
        $stat_type->code = $request->input('code');

        $stat_type->save();

        return new StatTypeResource($stat_type);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $stat_type = StatType::find($id);        
        $stat_type->delete();

        return new StatTypeResource($stat_type);
    }

    public static function refreshModelOrders(){
        $stat_types = StatType::orderBy('order', 'ASC')->get();

        $counter = 0;
        foreach($stat_types as $stat_type){
            $stat_type->order = $counter++;
            $stat_type->save();
        }
    }
}
