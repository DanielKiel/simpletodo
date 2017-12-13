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

Route::middleware(['api','auth:api'])->resource('lists', 'API\ListsController');

Route::middleware(['api','auth:api'])->resource('list-files', 'API\ListFilesController');

Route::middleware(['api','auth:api'])->resource('comments', 'API\CommentsController');

Route::middleware(['api','auth:api'])->resource('tenants', 'API\TenantsController');

Route::middleware(['api','auth:api'])->resource('users', 'API\UsersController');

Route::middleware(['api','auth:api'])->get('/tokens', 'API\ListsController@tokens')->name('api.tokens');

Route::middleware(['api','auth:api'])->get('/share/{token}/{userId}', 'API\ListsController@share')->name('api.share');

Route::middleware(['api','auth:api'])->get('/unshare/{token}/{userId}', 'API\ListsController@unshare')->name('api.unshare');