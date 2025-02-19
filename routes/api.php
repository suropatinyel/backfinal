<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AlatController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PelangganDataController;
use App\Http\Controllers\PenyewaanController;
use App\Http\Controllers\PenyewaanDetailController;
use App\Http\Controllers\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/refresh', [AuthController::class, 'refresh']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/forgot_password', [AuthController::class, 'forgotPassword']);
Route::post('/reset_password', [AuthController::class, 'resetPassword']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
Route::get('/user', [AuthController::class, 'index'])->middleware('auth:api');


//Route no login ALAT
Route::prefix('alat')->group(function (){
    Route::get('/', [AlatController::class, 'index']);
    Route::get('/show/{id}', [AlatController::class, 'show']);
});
Route::prefix('kategori')->group(function (){
    Route::get('/', [KategoriController::class, 'index']);
    Route::get('/show/{id}', [KategoriController::class, 'index']);
});


//Route ALAT
Route::prefix('alat')->middleware('auth:api')->group(function(){
    Route::post('/create', [AlatController::class, 'store']);
    Route::put('/put/{id}', [AlatController::class, 'update']);
    Route::patch('/patch/{id}', [AlatController::class, 'updatePatch']);
    Route::delete('/delete{id}', [AlatController::class, 'destroy']);
});


//Route KATEGORI
Route::prefix('kategori')->middleware('auth:api')->group(function(){
    Route::post('/create', [KategoriController::class, 'store']);
    Route::put('/put/{id}', [KategoriController::class, 'updatePut']);
    Route::delete('/delete/{id}', [KategoriController::class, 'destroy']);
});


//Route PELANGGAN
Route::prefix('pelanggan')->middleware('auth:api')->group(function(){
    Route::get('/', [PelangganController::class, 'index']);
    Route::get('/show/{id}', [PelangganController::class, 'index']);
    Route::post('/create', [PelangganController::class, 'store']);
    Route::put('/put/{id}', [PelangganController::class, 'updatePut']);
    Route::patch('/patch/{id}', [PelangganController::class, 'updatePatch']);
    Route::delete('/delete/{id}', [PelangganController::class, 'destroy']);
});


//Route PELANGGAN DATA
Route::prefix('pelanggan_data')->middleware('auth:api')->group(function(){
    Route::get('/', [PelangganDataController::class, 'index']);
    Route::get('/show/{id}', [PelangganDataController::class, 'index']);    
    Route::post('/create', [PelangganDataController::class, 'store']);
    Route::put('/put/{id}', [PelangganDataController::class, 'updatePut']);
    Route::patch('/patch/{id}', [PelangganDataController::class, 'updatePatch']);
    Route::delete('/delete/{id}', [PelangganDataController::class, 'destroy']);
});


//Route PENYEWAAN
Route::prefix('penyewaan')->middleware('auth:api')->group(function(){
    Route::get('/', [PenyewaanController::class, 'index']);
    Route::get('/show/{id}', [PenyewaanController::class, 'index']);
    Route::post('/create', [PenyewaanController::class, 'store']);
    Route::put('/put/{id}', [PenyewaanController::class, 'updatePut']);
    Route::patch('/patch/{id}', [PenyewaanController::class, 'updatePatch']);
    Route::delete('/delete/{id}', [PenyewaanController::class, 'destroy']);
});


//Route PENYEWAAN DETAIL
Route::prefix('penyewaan_detail')->middleware('auth:api')->group(function(){
    Route::get('/', [PenyewaanDetailController::class, 'index']);
    Route::get('/show/{id}', [PenyewaanDetailController::class, 'index']);
    Route::post('/create', [PenyewaanDetailController::class, 'store']);
    Route::put('/put/{id}', [PenyewaanDetailController::class, 'updatePut']);
    Route::patch('/patch/{id}', [PenyewaanDetailController::class, 'updatePatch']);
    Route::delete('/delete/{id}', [PenyewaanDetailController::class, 'destroy']);
});

// // Route::group(['middleware' => 'auth:api'], function () {
    
// Route::apiResource('/admin', AdminController::class);
// Route::apiResource('/kategori', KategoriController::class);
// Route::apiResource('/pelanggan', PelangganController::class);
// Route::apiResource('/pelanggan_data', PelangganDataController::class);
// Route::apiResource('/penyewaan', PenyewaanController::class);
// Route::patch('/penyewaan/patch/{id}', [PenyewaanController::class, 'updatePatch']);
// Route::apiResource('/penyewaan_detail', PenyewaanDetailController::class);

// });