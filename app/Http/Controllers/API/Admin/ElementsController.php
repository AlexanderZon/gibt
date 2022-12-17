<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\Admin\Elements\ElementResource;
use App\Models\Element;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ElementsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $elements = Element::all();

        return ElementResource::collection($elements);
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
        $element = new Element();
        $element->name = $request->input('name');

        self::refreshModelOrders();
        $total_elements = Element::all()->count();
        $element->order = $total_elements+1;
        $element->save();

        return new ElementResource($element);
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
        $element = Element::find($id);
        $element->name = $request->input('name');

        $element->save();

        return new ElementResource($element);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $element = Element::find($id);        
        $element->delete();

        return new ElementResource($element);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function picture(Request $request, $id)
    {        
        $path = Storage::put('elements/icons', $request->file('file'));

        $element = Element::find($id);
        $element->icon = $path;
        $element->save();

        self::refreshModelOrders();

        return new ElementResource($element);
    }

    public static function refreshModelOrders(){
        $elements = Element::orderBy('order', 'ASC')->get();

        $counter = 0;
        foreach($elements as $element){
            $element->order = $counter++;
            $element->save();
        }
    }
}
