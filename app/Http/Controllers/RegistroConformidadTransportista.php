<?php

namespace App\Http\Controllers;

use App\Mail\EntregaGuias;
use App\Mail\ModificacionEntregaArchivos;
use App\Models\CorreoConfig;
use App\Models\Observacion;
use App\Models\ObservacionRegistro;
use App\Models\RegistroConformidad;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class RegistroConformidadTransportista extends Controller
{
    public function index(Request $request)
    {
        $registros = [];
        $filtro = false;
        $inicio = $request->query("inicio");
        $fin = $request->query("fin");
        $estado = $request->query("estado");
        if ($estado) {
            $filtro = true;
            $registros = DB::select('select * from registro_conformidad where sap_transportista=? and estado_tracking=?  and (hora_de_ingreso between ? and ? or hora_de_salida between ? and ?)', [auth("transportista")->user()->sap, $estado, $inicio, $fin, $inicio, $fin]);
            if (!$registros) $registros = [];
        } else if (!$estado && $inicio) {
            $filtro = true;
            $registros = DB::select('select * from registro_conformidad where sap_transportista=? and (hora_de_ingreso between ? and ? or hora_de_salida between ? and ?)', [auth("transportista")->user()->sap, $inicio, $fin, $inicio, $fin]);
            if (!$registros) $registros = [];
        }
        return view('panel_transportista.registro_conformidad.guias', compact('registros', "filtro", "estado", "inicio", "fin"));
    }
    public function archivos(Request $request, $id)
    {
        $registro = RegistroConformidad::find($id);
        if (!$registro) {
            return abort(404);
        }
        $guias_remision = explode("/", $registro->guias_de_remision);
        $guiasentregadas = [];
        $archivos = [];
        $guiaspendientes = [];
        if ($registro->pdf_guia_transportista) {
            $nrguiasentregadas = explode(";", $registro->pdf_guia_transportista);
            $archivos = explode(";", $registro->pdf_guia_transportista);
            foreach ($nrguiasentregadas as $guias_e) {
                if ($guias_e) {
                    $guiaentregada = explode("_", $guias_e)[3];
                    array_push($guiasentregadas, trim($guiaentregada));
                }
            }
            if (count($guiasentregadas)) {
                foreach ($guias_remision as $grr) {
                    $sincaracter = trim($grr, "*");
                    if (!in_array($grr, $guiasentregadas) && !in_array($sincaracter, $guiasentregadas)) {
                        array_push($guiaspendientes, $grr);
                    }
                }
            } else {
                $guiaspendientes = $guias_remision;
            }
        } else {
            $guiasentregadas = [];
            $guiaspendientes = $guias_remision;
        }
        return view('panel_transportista.registro_conformidad.archivos', compact("registro", "archivos", "guiasentregadas", "guiaspendientes"));
    }
    public function observaciones(Request $request, $id)
    {
        $observaciones = Observacion::all();
        $registro = RegistroConformidad::find($id);
        return view('panel_transportista.registro_conformidad.observaciones', compact("registro", "observaciones"));
    }
    public function archivoStore(Request $request, $guia_remision)
    {
        $nroguia_remision = trim($guia_remision, "*");
        try {
            $request->validate([
                "id" => "required",
                "guia_cobranza" => "required",
                "guia_transportista" => "required"
            ]);
            $archivo_cobranza = $request->file("guia_cobranza");
            $registro = RegistroConformidad::findOrFail($request->id);
            $link1 = "";
            $archivo_cobranza_path = random_int(1, 10000000) . "_cobranza_" . $nroguia_remision . '_.' . $request->file("guia_cobranza")->getClientOriginalExtension();
            Storage::putFileAs("/" . $registro->sap_transportista, $archivo_cobranza, $archivo_cobranza_path);
            $tempvalue1 = "/pdf_guias/" . $registro->sap_transportista . "/" . $archivo_cobranza_path . ";" . $registro->pdf_guia_cobranza;
            if (!$registro->pdf_guia_cobranza) {
                $tempvalue1 = "/pdf_guias/" . $registro->sap_transportista . "/" . $archivo_cobranza_path;
            }
            $registro->pdf_guia_cobranza = $tempvalue1;
            $link1 = "/pdf_guias/" . $registro->sap_transportista . "/" . $archivo_cobranza_path;
            $link2 = "";
            $archivo_transportista = $request->file("guia_transportista");
            $archivo_transportista_path = random_int(1, 10000000) . "_cobranza_" . $nroguia_remision . '_.' . $request->file("guia_transportista")->getClientOriginalExtension();
            Storage::putFileAs("/" . $registro->sap_transportista, $archivo_transportista, $archivo_transportista_path);
            $tempvalue2 = "/pdf_guias/" . $registro->sap_transportista . "/" . $archivo_transportista_path . ";" . $registro->pdf_guia_transportista;
            if (!$registro->pdf_guia_transportista) {
                $tempvalue2 = "/pdf_guias/" . $registro->sap_transportista . "/" . $archivo_transportista_path;
            }
            $registro->pdf_guia_transportista = $tempvalue2;
            $link2 = "/pdf_guias/" . $registro->sap_transportista . "/" . $archivo_transportista_path;
            if (count(explode(";", $registro->pdf_guia_transportista)) >= count(explode("/", $registro->guias_de_remision))) {
                if (count($registro->observaciones)) {
                    $registro->estado_tracking = "NO OK";
                } else {
                    $registro->estado_tracking = "OK";
                }
            }
            $registro->save();
            try {
                $allcorreosconfig = CorreoConfig::all();
                $detinatarios = [];
                foreach ($allcorreosconfig as $correoc) {
                    array_push($detinatarios, $correoc->correo);
                }
                $correoenvio = new EntregaGuias($registro, public_path() . $link2, public_path() . $link1, $nroguia_remision);
                Mail::to($detinatarios[0])->cc($detinatarios)->send($correoenvio);
                return back()->with("success",  "Archivos cargados correctamente y correo enviado");
            } catch (Exception $ex) {
                return back()->with("error", "Archivos cargados correctamente pero no se puedo enviar el correo hagalo manualmente, detalle error  : " . $ex->getMessage());
            }
        } catch (Exception $e) {
            return back()->with("error", $e->getMessage());
        }
    }
    public function archivoStoreUpdate(Request $request)
    {

        try {
            $request->validate([
                "id" => "required",
                "guia_cobranza" => "required",
                "guia_transportista" => "required",
                "nroguiaeditar" => "required"

            ]);
            $nroguia_remision = trim($request->nroguiaeditar, "*");
            $archivo_transportista = $request->file("guia_transportista");
            $registro = RegistroConformidad::findOrFail($request->id);
            $nvlink = "";
            if ($registro->pdf_guia_transportista) {
                $archivosubidos = explode(";", $registro->pdf_guia_transportista);
                foreach ($archivosubidos as $archivo) {
                    $nrguia = trim(explode("_", $archivo)[3], "*");
                    if ($nrguia == $nroguia_remision) {
                        Storage::delete([$archivo]);
                    } else {
                        if ($nvlink) {
                            $nvlink .= ";" . $archivo;
                        } else {
                            $nvlink = $archivo;
                        }
                    }
                }
            }
            $registro->pdf_guia_transportista = $nvlink;
            $link1 = "";
            $archivo_transportista_path = random_int(1, 10000000) . "_transportista_" . $nroguia_remision . '_.' . $request->file("guia_transportista")->getClientOriginalExtension();
            Storage::putFileAs("/" . $registro->sap_transportista, $archivo_transportista, $archivo_transportista_path);
            $tempvalue1 = "/pdf_guias/" . $registro->sap_transportista . "/" . $archivo_transportista_path . ";" . $registro->pdf_guia_transportista;
            if (!$registro->pdf_guia_transportista) {
                $tempvalue1 = "/pdf_guias/" . $registro->sap_transportista . "/" . $archivo_transportista_path;
            }
            $registro->pdf_guia_transportista = $tempvalue1;
            $link1 = "/pdf_guias/" . $registro->sap_transportista . "/" . $archivo_transportista_path;
            $link2 = "";
            $archivo_cobranza = $request->file("guia_cobranza");
            $nvlink2 = "";
            if ($registro->pdf_guia_cobranza) {
                $archivosubidos2 = explode(";", $registro->pdf_guia_cobranza);
                foreach ($archivosubidos2 as $archivoo) {
                    $nrguia = trim(explode("_", $archivoo)[3], "*");
                    if ($nrguia == $nroguia_remision) {
                        Storage::delete([$archivoo]);
                    } else {
                        if ($nvlink2) {
                            $nvlink2 .= ";" . $archivoo;
                        } else {
                            $nvlink2 = $archivoo;
                        }
                    }
                }
            }
            $registro->pdf_guia_cobranza = $nvlink2;
            $archivo_cobranza_path = random_int(1, 10000000) . "_cobranza_" . $nroguia_remision . '_.' . $request->file("guia_cobranza")->getClientOriginalExtension();
            Storage::putFileAs("/" . $registro->sap_transportista, $archivo_cobranza, $archivo_cobranza_path);
            $tempvalue2 = "/pdf_guias/" . $registro->sap_transportista . "/" . $archivo_cobranza_path . ";" . $registro->pdf_guia_cobranza;
            if (!$registro->pdf_guia_cobranza) {
                $tempvalue2 = "/pdf_guias/" . $registro->sap_transportista . "/" . $archivo_cobranza_path;
            }
            $registro->pdf_guia_cobranza = $tempvalue2;
            $link2 = "/pdf_guias/" . $registro->sap_transportista . "/" . $archivo_cobranza_path;
            if (count(explode(";", $registro->pdf_guia_transportista)) >= count(explode("/", $registro->guias_de_remision))) {
                if (count($registro->observaciones)) {
                    $registro->estado_tracking = "NO OK";
                } else {
                    $registro->estado_tracking = "OK";
                }
            }
            $registro->save();
            try {
                $allcorreosconfig = CorreoConfig::all();
                $detinatarios = [];
                foreach ($allcorreosconfig as $correoc) {
                    array_push($detinatarios, $correoc->correo);
                }
                $correoenvio = new ModificacionEntregaArchivos($registro, public_path() . $link2, public_path() . $link1, $nroguia_remision);
                Mail::to($detinatarios[0])->cc($detinatarios)->send($correoenvio);
                return back()->with("success",  "Archivos cargados correctamente y correo enviado");
            } catch (Exception $ex) {
                return back()->with("error", "Archivos cargados correctamente pero no se puedo enviar el correo hagalo manualmente, detalle error  : " . $ex->getMessage());
            }
        } catch (Exception $e) {
            return back()->with("error", $e->getMessage() . $e->getLine());
        }
    }
    public function observacionStore(Request $request)
    {
        $request->validate([
            "observacion" => "required",
            "registro_id" => "required",
            "cantidad" => "required"
        ]);
        if (!$request->observacion) {
            return back()->with("error", "Seleccione su observacion y vuelva a intentarlo");
        }
        if ($request->observacion == "new") {
            $observacion = new Observacion();
            if ($request->newobservacion) {
                $observacion->nombre = $request->newobservacion;
                $observacion->save();
                $ultimaobservacion = Observacion::max("id");
                $newobservacionregistro = new ObservacionRegistro();
                $newobservacionregistro->registro_conformidad_id = $request->registro_id;
                $newobservacionregistro->cantidad = $request->cantidad;
                $newobservacionregistro->observacion_id = $ultimaobservacion;
                $newobservacionregistro->save();
                $registro = RegistroConformidad::find($request->registro_id);
                if ($registro) {
                    if ($registro->pdf_guia_transportista) {
                        if (count(explode(";", $registro->pdf_guia_transportista)) >= count(explode("/", $registro->guias_de_remision))) {
                            if (count($registro->observaciones)) {
                                $registro->estado_tracking = "NO OK";
                            } else {
                                $registro->estado_tracking = "OK";
                            }
                            $registro->save();
                        }
                    }
                }
                return back()->with("success", "Se agrego correctamente");
            }
            return back()->with("error", "Seleccione su observacion y vuelva a intentarlo");
        } else {
            $newobservacionregistro = new ObservacionRegistro();
            $newobservacionregistro->registro_conformidad_id = $request->registro_id;
            $newobservacionregistro->observacion_id = $request->observacion;
            $newobservacionregistro->cantidad = $request->cantidad;
            $newobservacionregistro->save();
            $registro = RegistroConformidad::find($request->registro_id);
            if ($registro) {
                if ($registro->pdf_guia_transportista) {
                    if (count(explode(";", $registro->pdf_guia_transportista)) >= count(explode("/", $registro->guias_de_remision))) {
                        if (count($registro->observaciones)) {
                            $registro->estado_tracking = "NO OK";
                        } else {
                            $registro->estado_tracking = "OK";
                        }
                        $registro->save();
                    }
                }
            }
            return back()->with("success", "Se agrego correctamente");
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($registro_conformidad_id, $observacion_id)
    {
        try {
            $o_registro = ObservacionRegistro::where("registro_conformidad_id", $registro_conformidad_id)->where("observacion_id", $observacion_id)->first();
            $o_registro->delete();
            $registro = RegistroConformidad::find($registro_conformidad_id);

            if ($registro) {
                if ($registro->pdf_guia_transportista) {
                    if (count(explode(";", $registro->pdf_guia_transportista)) >= count(explode("/", $registro->guias_de_remision))) {
                        if (count($registro->observaciones)) {
                            $registro->estado_tracking = "NO OK";
                        } else {
                            $registro->estado_tracking = "OK";
                        }
                        $registro->save();
                    }
                }
            }
            return back()->with("success", "Se elimino correctamente");
        } catch (Exception $e) {
            return back()->with("error", $e->getMessage());
        }
    }
    public function destroy($id)
    {
        //
    }
}
