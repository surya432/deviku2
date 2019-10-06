<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/embed/{url}', 'EmbedController@getEmbed')->name('embed');
Route::post('/embed', 'EmbedController@getPlayer')->name('getPlayer');
Route::get('/deletegd', 'TrashController@AutoDeleteGd')->name('AutoDeleteGd');
Route::get('/new', function () {
    return view('vendor.adminlte.register');
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/session', function () {
    return [
        "auth_web" => Auth::user(),
        "tokenapi" => session('token'),
        // "supplier" => session('supplier')
    ];
})->name('session');
Route::get('/cmpcode', function () {
    return [
       
        "cmp_id" => \App\User::find(1)->with('cmpcode'),
        // "supplier" => session('supplier')
    ];
})->name('session');
Route::prefix('/superadmin')->group(function () {
    Route::resource('roles', 'RoleController');
    Route::resource('users', 'UserController');
    Route::resource('master-mirror', 'MasterMirrorController');
    Route::resource('mirrorkey', 'MirrorkeyController');
    Route::resource('category', 'CategoryController');
    Route::resource('permission', 'PermissionController');
});
Route::prefix('/admin')->group(function () {
    Route::resource('users', 'UserController');
    Route::resource('mirrorkey', 'MirrorkeyController');
});
Route::prefix('/home')->group(function () {
    Route::resource('post', 'PostController');
    Route::resource('content', 'ContentController');
    Route::resource('metalink', 'MetaLinkController');
});
Route::prefix('/admin/ajax/master/')->group(function () {
    Route::get('users', 'UserController@getDataMaster')->name('ajax.master.users');
    Route::post('users', 'UserController@create')->name('ajax.master.users');
    Route::put('users', 'UserController@update')->name('ajax.master.users');
    Route::delete('users', 'UserController@update')->name('ajax.master.users');
});