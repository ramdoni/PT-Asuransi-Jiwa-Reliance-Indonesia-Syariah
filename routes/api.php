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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/',function(){
    return ['message'=>'oke'];
});
Route::post('auth-login',[\App\Http\Controllers\Api\UserController::class,'login']);
Route::post('get-pengajuan',[\App\Http\Controllers\Api\PengajuanController::class,'data']);
Route::post('pengajuan/store',[\App\Http\Controllers\Api\PengajuanController::class,'store']);
Route::post('get-polis',[\App\Http\Controllers\Api\PolisController::class,'data']);
Route::post('get-pengajuan-peserta',[\App\Http\Controllers\Api\PengajuanController::class,'dataPeserta']);
