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

Route::get('guru', [GuruController::class, 'index']);

Route::get('kelas', [KelasController::class, 'index']);

Route::get('matapelajaran', [MataPelajaranController::class, 'index']);

Route::get('notification', [NotificationController::class, 'index']);

Route::get('role', [RoleController::class, 'index']);

Route::get('alokasikelas', [AlokasiKelasController::class, 'index']);