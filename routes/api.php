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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test', function (){

});

/*** route for Display a listing of the category ***/
Route::get('/categories', [App\Http\Controllers\CategoryContoller::class,'index']);

/*** route for store data into database ***/
Route::post('/store', [App\Http\Controllers\CategoryContoller::class,'store']);

/*** route for Display the specified category ***/
Route::get('/category/{id}', [App\Http\Controllers\CategoryContoller::class,'show']);

/*** route for update data into database ***/
Route::put('/update/{id}', [App\Http\Controllers\CategoryContoller::class,'update']);

/*** route for delete data from database ***/
Route::delete('/destroy/{id}', [App\Http\Controllers\CategoryContoller::class,'destroy']);

