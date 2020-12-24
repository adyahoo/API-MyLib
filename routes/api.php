<?php

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

//loginAndroid
//Route::post('/loginAndroid','APIController@loginAndroid');

Route::post('login','Api\AuthController@login');
Route::post('register','Api\AuthController@register');
Route::get('logout','Api\AuthController@logout');
Route::post('user_info','Api\AuthController@userInfo')->middleware('jwtAuth');

// Route::post('edit_profile','Api\UserController@editProfile')->middleware('jwtAuth');
Route::get('notif','Api\BookController@notifStore');

Route::post('createBook','Api\BookController@StoreBook')->middleware('jwtAuth');
Route::post('editBook','Api\BookController@EditBook')->middleware('jwtAuth');
Route::post('searchBook','Api\BookController@SearchBook')->middleware('jwtAuth');
Route::post('deleteBook','Api\BookController@DestroyBook')->middleware('jwtAuth');
Route::get('getBook','Api\BookController@getBooks')->middleware('jwtAuth');
Route::get('getAllBooks','Api\BookController@getAllBooks');