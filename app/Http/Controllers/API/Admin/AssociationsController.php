<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\Admin\Associations\AssociationResource;
use App\Models\Association;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssociationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $associations = Association::all();

        return AssociationResource::collection($associations);
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
        $association = new Association();
        $association->name = $request->input('name');

        self::refreshModelOrders();
        $total_associations = Association::all()->count();
        $association->order = $total_associations+1;
        $association->save();

        return new AssociationResource($association);
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
        $association = Association::find($id);
        $association->name = $request->input('name');

        $association->save();

        return new AssociationResource($association);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $association = Association::find($id);        
        $association->delete();

        return new AssociationResource($association);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function picture(Request $request, $id)
    {        
        $path = Storage::put('associations/icons', $request->file('file'));

        $association = Association::find($id);
        $association->icon = $path;
        $association->save();

        self::refreshModelOrders();

        return new AssociationResource($association);
    }

    public static function refreshModelOrders(){
        $associations = Association::orderBy('order', 'ASC')->get();

        $counter = 0;
        foreach($associations as $association){
            $association->order = $counter++;
            $association->save();
        }
    }
}
