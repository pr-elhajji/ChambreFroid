<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

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
// carte
Route::post('/data/add', ['uses' => 'CarteController@setdata']);
Route::post('/carte/add', ['uses' => 'CarteController@create_carte']);

//Chambres
Route::resource('chambres', ChambreController::class);
Route::get('/chambres/capacite/{id}', ['uses' => 'ChambreController@getCapacite']);
Route::get('/capacites/all', ['uses' => 'ChambreController@getCapaciteAll']);
Route::get('/chambres/temphum/{id}/{interval}', ['uses' => 'ChambreController@getTempHum']);
Route::get('/chambres/lots/{id}',['uses'=>'ChambreController@getLots']);
Route::post('/chambres/addlots/',['uses'=>'ChambreController@addLots']); //add lot

Route::get('/chambres/alerts/{id}/{interval}', ['uses' => 'ChambreController@getAlerts']); //alerte
Route::get('/machines/temphumstat/{id}/{typestat}', ['uses'=> 'chambreController@getSate']); //stat TEmp Hum tdb


//lots
Route::resource('lots', LotController::class);

//Varietes
Route::resource('varietes', VarieteController::class);

//users
Route::get('/users/profile/{id}', [App\Http\Controllers\UsersController::class, 'show']);

//repports
Route::post('/raports/find/', [App\Http\Controllers\ReportController::class, 'find']);


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/menus', 'MenusController@index');

Route::post('/register', [App\Http\Controllers\AuthenticationController::class, 'store']);
Route::get('/user', [App\Http\Controllers\AuthenticationController::class, 'user']);

Route::post('/login', [App\Http\Controllers\AuthenticationController::class, 'login']);

Route::group(['middleware' => 'auth:api'], function() {
    Route::post('logout', [App\Http\Controllers\AuthenticationController::class, 'logout']);
});

Route::group([
    'namespace' => 'Auth',
    'middleware' => 'api',
    'prefix' => 'password'
], function () {
    Route::post('create', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'create']);
    Route::get('find/{token}', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'find']);
    Route::post('reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'reset']);
});

Route::get('/redirect', function () {
    $query = http_build_query([
        'client_id' => 'client-id',
        'redirect_uri' => 'http://example.com/callback',
        'response_type' => 'code',
        'scope' => 'place-orders check-status',
    ]);
    return redirect('http://your-app.com/oauth/authorize?' . $query);
});
