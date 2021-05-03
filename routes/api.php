<?php

use App\Http\Controllers\API\AlokasiKelasController;
use App\Http\Controllers\API\GuruController;
use App\Http\Controllers\API\KelasController;
use App\Http\Controllers\API\MataPelajaranController;
use App\Http\Controllers\API\MuridController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\UserController;
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

Route::get('murid', [MuridController::class, 'index']);
Route::get('murid/{id}', [MuridController::class, 'show']);

Route::get('user', [UserController::class, 'index']);
Route::get('user/{id}',[UserController::class, 'show']);
Route::post('user/add',[UserController::class, 'store']);
Route::post('user/update/{id}',[UserController::class, 'update']);
Route::post('user/delete/{id}',[UserController::class, 'destroy']);

Route::get('guru', [GuruController::class, 'index']);
Route::get('guru/{id}', [GuruController::class, 'show']);

Route::get('kelas', [KelasController::class, 'index']);
route::post('kelas/add', [KelasController::class, 'store']);
Route::get('kelas/{id}', [KelasController::class, 'show']);
Route::post('kelas/update/{id}', [KelasController::class, 'update']);
Route::post('kelas/delete/{id}', [KelasController::class, 'destroy']);

Route::get('matapelajaran', [MataPelajaranController::class, 'index']);
Route::post('matapelajaran/add', [MataPelajaranController::class, 'store']);
Route::get('matapelajaran/{id}', [MataPelajaranController::class, 'show']);
Route::post('matapelajaran/update/{id}', [MataPelajaranController::class, 'update']);
Route::delete('matapelajaran/delete/{id}', [MataPelajaranController::class, 'destroy']);

Route::get('notification', [NotificationController::class, 'index']);

Route::get('role', [RoleController::class, 'index']);
Route::get('role/{id}', [RoleController::class, 'show']);
Route::post('role/add', [RoleController::class, 'store']);

Route::get('alokasikelas', [AlokasiKelasController::class, 'index']);