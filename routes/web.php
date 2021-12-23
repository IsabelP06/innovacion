<?php

use App\Http\Controllers\AuthTransportista;
use App\Http\Controllers\CorreoConfigController;
use App\Http\Controllers\IndicadoresController;
use App\Http\Controllers\RegistroConformidadController;
use App\Http\Controllers\RegistroConformidadTransportista;
use App\Http\Controllers\SedeController;
use App\Http\Controllers\TransportistaController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
})->middleware(["guest"]);
Route::get("/transportista/login", [AuthTransportista::class, "login"])->middleware("guesttranportista");
Route::post("transportista/auth", [AuthTransportista::class, "authenticate"])->name("authenticatetransportista");
Route::post("transportista/logout", [AuthTransportista::class, "logout"])->name("logouttransportista");
Route::middleware(["authtransportista"])->group(function () {
    Route::post("transportista/change_password", [AuthTransportista::class, "changePassword"])->name("changepassword.transportista");
    Route::post("transportista/change_correo", [AuthTransportista::class, "changeCorreo"])->name("changecorreo.transportista");
});
Route::prefix("panel")->group(function () {
    Route::middleware(["authtransportista"])->group(function () {
        Route::get("/", [AuthTransportista::class, "index"]);
        Route::get("registro_conformidad", [RegistroConformidadTransportista::class, "index"])->name("registro_conformidad_index.transportista");
        Route::get("registro_conformidad/{id}/archivos", [RegistroConformidadTransportista::class, "archivos"]);
        Route::get("registro_conformidad/{id}/observaciones", [RegistroConformidadTransportista::class, "observaciones"]);
        Route::post("registro_conformidad/archivos/{guia}", [RegistroConformidadTransportista::class, "archivoStore"])->name("uploadfiles.transportista");
        Route::post("registro_conformidad/archivosupdate", [RegistroConformidadTransportista::class, "archivoStoreUpdate"])->name("updatefiles.transportista");
        Route::post("registro_conformidad/observacion", [RegistroConformidadTransportista::class, "observacionStore"])->name("observacion_store.transportista");
        Route::delete("registro_conformidad/observacion/delete/{registro_conformidad_id}/{observacion_id}", [RegistroConformidadTransportista::class, "delete"])->name("eliminarobservacion.transportista");
    });
});
Route::prefix("dashboard")->group(function () {
    Route::middleware(["auth"])->group(function () {
        Route::get('/', function () {
            return view('home');
        })->name('dashboard');
        Route::resource('usuario', UsuarioController::class);
        Route::post('message_request_guias', [RegistroConformidadController::class, "requestGuias"]);
        Route::post("transportista/importar", [TransportistaController::class, "importarExcel"])->name("transportista.importarexcel");
        Route::resource('transportista', TransportistaController::class);
        Route::post('registro_conformidad/queryParams', [RegistroConformidadController::class, "queryParams"])->name("registro_conformidad.queryParams");
        Route::post("indicadores", [IndicadoresController::class, "indicadoresFormHome"]);
        Route::get("registros_de_conformidad",[RegistroConformidadController::class, "getRegistersByQuery"]);
        Route::get('registro_observaciones', [RegistroConformidadController::class,"observaciones"]);
        Route::resource("registro_conformidad", RegistroConformidadController::class);
        Route::resource('sedes', SedeController::class);
        Route::resource('config/correos', CorreoConfigController::class);
    });
});
require __DIR__ . '/auth.php';
