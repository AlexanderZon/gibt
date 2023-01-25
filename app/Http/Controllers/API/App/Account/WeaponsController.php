<?php

namespace App\Http\Controllers\API\App\Account;

use App\Exceptions\API\App\Accounts\Weapons\WeaponDoNotBelongsToActualAccountException;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Resources\API\App\Accounts\Weapons\WeaponResource as WeaponsWeaponResource;
use App\Http\Resources\API\App\Weapons\WeaponResource;
use App\Models\Weapon;
use App\Models\Account\Weapon as AccountWeapon;
use Illuminate\Http\Request;

class WeaponsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $list = $request->actualAccount->accountWeapons;
        $list->load([
            'weapon.weaponIcon',
            'weapon.weaponAwakenedIcon'
        ]);

        return [
            'list' => WeaponsWeaponResource::collection($list)
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $weapons_list = Weapon::where('released','=',1)->get();
        $weapons_list->load(['weaponIcon']);

        $model = new AccountWeapon();
        return [
            'form' => [
                'weapons' => WeaponResource::collection($weapons_list)
            ],
            'model' => $model
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
        $model = new AccountWeapon();
        $model->account_id = $request->actualAccount->id;
        $model->weapon_id = $request->input('weapon.id');
        $model->level = $request->input('level');
        $model->refinement_rank = $request->input('refinement_rank');
        $model->save();

        $model->load(['weapon.weaponIcon']);

        return new WeaponsWeaponResource($model);
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
    public function edit(Request $request, $id)
    {
        $model = AccountWeapon::find($id);
        if($model->account_id != $request->actualAccount->id) throw new WeaponDoNotBelongsToActualAccountException();
        
        $model->load(['weapon.weaponIcon']);

        $weapons_list = Weapon::where('released','=',1)->get();
        $weapons_list->load(['weaponIcon']);


        return [
            'form' => [
                'weapons' => WeaponResource::collection($weapons_list)
            ],
            'model' => new WeaponsWeaponResource($model)
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
        $model = AccountWeapon::find($id);
        if($model->account_id != $request->actualAccount->id) throw new WeaponDoNotBelongsToActualAccountException();
        $model->account_id = $request->actualAccount->id;
        $model->weapon_id = $request->input('weapon.id');
        $model->level = $request->input('level');
        $model->refinement_rank = $request->input('refinement_rank');
        $model->save();

        $model->load(['weapon.weaponIcon']);

        return new WeaponsWeaponResource($model);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $model = AccountWeapon::find($id);
        if($model->account_id != $request->actualAccount->id) throw new WeaponDoNotBelongsToActualAccountException();

        $model->delete();
        return new WeaponsWeaponResource($model);
    }
}
