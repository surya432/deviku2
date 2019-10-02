<?php

use Illuminate\Http\Request;

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


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['middleware' => 'auth:api'], function () { //wajib-dengan-token
    Route::prefix('/ajax')->group(function () {
        Route::get('/form-master-mirror', 'MasterMirrorController@json')->name('ApiMasterMirrorJson');
        Route::post('/form-master-mirror-create', 'MasterMirrorController@store')->name('ApiMasterMirrorStore');
        Route::get('/mirror/datatabel', 'MirrorKeyController@json')->name('Apimirrorkeyjson');
        Route::get('/category/datatabel', 'CategoryController@json')->name('ApiCategoryjson');
        Route::get('/post/datatabel', 'PostController@json')->name('ApiPostjson');
        Route::get('/content/datatabel', 'ContentController@json')->name('ApiContentjson');
       

    });
});
