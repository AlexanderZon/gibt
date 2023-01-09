<?php

use App\Http\Middleware\API\Admin\CorsMiddleware;
use App\Http\Middleware\API\Crawler\AllowRequestMiddleware;
use App\Http\Middleware\API\App\GlobalMiddleware;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('admin')->middleware(CorsMiddleware::class)->namespace('App\Http\Controllers\API\Admin')->group(function(){
    Route::resource('ascension-material-types', AscensionMaterialTypesController::class);
    Route::post('ascension-materials/{ascension_material_id}/picture', AscensionMaterialsController::class.'@picture');
    Route::resource('ascension-materials', AscensionMaterialsController::class);
    Route::post('associations/{association_id}/picture', AssociationsController::class.'@picture');
    Route::resource('associations', AssociationsController::class);
    Route::resource('characters/{character_id}/skills', Characters\Skills\SkillsController::class);
    Route::resource('characters/{character_id}/stats', Characters\Stats\StatsController::class);
    Route::post('characters/{character_id}/picture', Characters\CharactersController::class.'@picture');
    Route::resource('characters', Characters\CharactersController::class);
    Route::post('elements/{element_id}/picture', ElementsController::class.'@picture');
    Route::resource('elements', ElementsController::class);
    Route::post('visions/{vision_id}/picture', VisionsController::class.'@picture');
    Route::resource('visions', VisionsController::class);
    Route::resource('stat-types', StatTypesController::class);
    Route::resource('weapons/{weapon_id}/stats', Weapons\Stats\StatsController::class);
    Route::post('weapons/{weapon_id}/picture', Weapons\WeaponsController::class.'@picture');
    Route::resource('weapons', Weapons\WeaponsController::class);
    Route::post('weapon-types/{weapon_type_id}/picture', WeaponTypesController::class.'@picture');
    Route::resource('weapon-types', WeaponTypesController::class);
});

Route::prefix('crawler')->middleware(AllowRequestMiddleware::class)->namespace('App\Http\Controllers\API\Crawler')->group(function(){
    Route::resource('weapons', Weapons\WeaponsController::class)->only(['store']);
    Route::resource('characters', Characters\CharactersController::class)->only(['store']);
});


Route::prefix('app')->namespace('App\Http\Controllers\API\App')->group(function(){
    Route::resource('auth/forgot', Auth\ForgotController::class);
    Route::resource('auth/signup', Auth\SignupController::class);
    Route::resource('auth/login', Auth\LoginController::class);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/app/users', function(){
        return User::all();
    });
    Route::resource('/app/auth/logout', App\Http\Controllers\API\App\Auth\LogoutController::class)->only(['store']);
});
