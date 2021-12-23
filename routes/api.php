<?php

use App\Http\Controllers\AuthTransportista;
use App\Http\Controllers\RegistroConformidadTransportistaAppController;
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
Route::post('/auth/user', [AuthTransportista::class, "loginWithToken"]);
Route::post('/auth/logued', [AuthTransportista::class, "autenticatedWithToken"]);
Route::post("/transportista/changepassword",[AuthTransportista::class,"changePassword2"]);
Route::post("/transportista/changecorreo",[AuthTransportista::class,"changeCorreo2"]);
Route::post("registro_conformidad", [RegistroConformidadTransportistaAppController::class, "index"]);
Route::get("/registro_conformidad/{id}",[RegistroConformidadTransportistaAppController::class, "show"]);
Route::get("registro_conformidad/{id}/archivos", [RegistroConformidadTransportistaAppController::class, "archivos"]);
Route::get("registro_conformidad/observaciones/{id}", [RegistroConformidadTransportistaAppController::class, "observaciones"]);
Route::post("registro_conformidad/archivos", [RegistroConformidadTransportistaAppController::class, "archivoStore"]);
Route::post("registro_conformidad/archivosupdate", [RegistroConformidadTransportistaAppController::class, "archivoStoreUpdate"]);
Route::post("registro_conformidad/observacion", [RegistroConformidadTransportistaAppController::class, "observacionStore"]);
Route::delete("registro_conformidad/observacion/{id}", [RegistroConformidadTransportistaAppController::class, "delete"]);
