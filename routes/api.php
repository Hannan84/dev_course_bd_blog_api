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


/***    Category Table Route ***/
/*** route for Display a listing of the category ***/
Route::get('/categories', [App\Http\Controllers\CategoryContoller::class,'index']);

/*** route for store category into database ***/
Route::post('/store', [App\Http\Controllers\CategoryContoller::class,'store']);

/*** route for Display the specified category ***/
Route::get('/category/{id}', [App\Http\Controllers\CategoryContoller::class,'show']);

/*** route for update category into database ***/
Route::put('/update/{id}', [App\Http\Controllers\CategoryContoller::class,'update']);

/*** route for delete category from database ***/
Route::delete('/destroy/{id}', [App\Http\Controllers\CategoryContoller::class,'destroy']);



/***    Blog Table Route    ***/
/*** route for Display a listing of the blogs ***/
Route::get('/blogs', [App\Http\Controllers\BlogController::class,'index']);

/*** route for store blog into database ***/
Route::post('/blog/store', [App\Http\Controllers\BlogController::class,'store']);

/*** route for Display the specified blog ***/
Route::get('/blog/{id}', [App\Http\Controllers\BlogController::class,'show']);

/*** route for store blog into database ***/
Route::put('/blog/update/{id}', [App\Http\Controllers\BlogController::class,'update']);

/*** route for delete blog from database ***/
Route::delete('/blog/destroy/{id}', [App\Http\Controllers\BlogController::class,'destroy']);
