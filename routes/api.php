<?php

use App\Http\Controllers\API\Admin as Admin;
use App\Http\Controllers\API\Crawler as Crawler;
use App\Http\Controllers\API\App as App;
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

Route::prefix('admin')->middleware(CorsMiddleware::class)->name('api.admin.')->group(function(){
    Route::resource('ascension-material-types', Admin\AscensionMaterialTypesController::class);
    Route::post('ascension-materials/{ascension_material_id}/picture', [ Admin\AscensionMaterialsController::class, 'picture' ]);
    Route::resource('ascension-materials', Admin\AscensionMaterialsController::class);
    Route::post('associations/{association_id}/picture', [ Admin\AssociationsController::class, 'picture' ]);
    Route::resource('associations', Admin\AssociationsController::class);
    Route::post('elements/{element_id}/picture', [ Admin\ElementsController::class, 'picture' ]);
    Route::resource('elements', Admin\ElementsController::class);
    Route::post('visions/{vision_id}/picture', [ Admin\VisionsController::class, 'picture' ]);
    Route::resource('visions', Admin\VisionsController::class);
    Route::resource('stat-types', Admin\StatTypesController::class);
    Route::post('weapon-types/{weapon_type_id}/picture', [ Admin\WeaponTypesController::class, 'picture' ]);
    Route::resource('weapon-types', Admin\WeaponTypesController::class);
    Route::prefix('characters')->name('characters.')->group(function(){
        Route::resource('{character_id}/skills', Admin\Characters\Skills\SkillsController::class);
        Route::resource('{character_id}/stats', Admin\Characters\Stats\StatsController::class);
        Route::post('{character_id}/picture', [ Admin\Characters\CharactersController::class, 'picture' ]);
    });
    Route::resource('characters', Admin\Characters\CharactersController::class);
    Route::prefix('weapons')->name('weapons.')->group(function(){
        Route::resource('{weapon_id}/stats', Admin\Weapons\Stats\StatsController::class);
        Route::post('{weapon_id}/picture', [ Admin\Weapons\WeaponsController::class, 'picture' ]);
    });
    Route::resource('weapons', Admin\Weapons\WeaponsController::class);
});

Route::prefix('crawler')->middleware(AllowRequestMiddleware::class)->name('api.crawler.')->group(function(){
    Route::resource('weapons', Crawler\Weapons\WeaponsController::class)->only(['store']);
    Route::resource('characters', Crawler\Characters\CharactersController::class)->only(['store']);
});

Route::prefix('app')->name('api.app.')->group(function(){
    Route::resource('/auth/forgot', App\Auth\ForgotController::class);
    Route::resource('/auth/signup', App\Auth\SignupController::class);
    Route::resource('/auth/login', App\Auth\LoginController::class);
    
    Route::resource('/auth/check', App\Auth\CheckController::class)->only(['index']);
    Route::middleware(['auth:sanctum', 'app.account'])->group(function () {
        Route::resource('/auth/logout', App\Auth\LogoutController::class)->only(['store']);
        Route::get('/account/dashboard', [ App\Account\DashboardController::class, 'index' ]);
        Route::post('/account/characters/list/add', [ App\Account\CharactersListController::class, 'add' ]);
        Route::post('/account/characters/list/remove', [ App\Account\CharactersListController::class, 'remove' ]);
        Route::resource('/account/characters', App\Account\CharactersController::class);
        Route::resource('/account/weapons', App\Account\WeaponsController::class);

        Route::put('/account/settings/accounts/active/{account_id}', [ App\Account\SettingsController::class, 'setActiveAccount' ]);
        Route::delete('/account/settings/accounts/{account_id}', [ App\Account\SettingsController::class, 'deleteAccounts' ]);
        Route::put('/account/settings/accounts/{account_id}', [ App\Account\SettingsController::class, 'updateAccounts' ]);
        Route::post('/account/settings/accounts', [ App\Account\SettingsController::class, 'storeAccounts' ]);
        Route::get('/account/settings', [ App\Account\SettingsController::class, 'index' ]);
    });
});

Route::middleware('auth:sanctum')->group(function () {
    //
});

