<?php

namespace App\Http\Controllers;

use App\Imports\RegistroConformidadImport;
use App\Mail\PedirGuias;
use App\Models\CorreoConfig;
use App\Models\ObservacionRegistro;
use App\Models\RegistroConformidad;
use App\Models\Sede;
use App\Models\Transportista;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class RegistroConformidadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sedes = Sede::all();       
        return view('registro_conformidad.index', compact( "sedes"));
    }
    public function observaciones(Request $request)
    {
        if($request->id){
            $observaciones = DB::table('observacion_registro_conformidad')->join("observacions" , "observacions.id" ,"=","observacion_registro_conformidad.observacion_id")->select("observacions.nombre","observacion_registro_conformidad.*")->where("observacion_registro_conformidad.registro_conformidad_id",$request->id)->get();
            return response()->json(["data"=>$observaciones,"success"=>true]);
        }
        return response()->json(['success' => false,"message"=>"Ah ocurrido un error"]);
    }
    public function getRegistersByQuery(Request $request){
        $registros = [];
        $filtro = false;
        $inicio = $request->query("inicio");
        $fin = $request->query("fin");
        $estado = $request->query("estado");
        $sedeselected = $request->query("sede");
        if ($estado && $sedeselected) {
            $filtro = true;
            $registros = RegistroConformidad::where("estado_tracking", $estado)->where("sede", $sedeselected)->where(function ($query) use ($inicio, $fin) {
                $query->whereBetween('hora_de_ingreso', [$inicio, $fin])
                    ->orWhereBetween('hora_de_salida', [$inicio, $fin]);
            })->get();
        } else if (!$estado && $sedeselected) {
            $filtro = true;
            $registros = RegistroConformidad::where("sede", $sedeselected)->where(function ($query) use ($inicio, $fin) {
                $query->whereBetween('hora_de_ingreso', [$inicio, $fin])
                    ->orWhereBetween('hora_de_salida', [$inicio, $fin]);
            })->get();
        } else if ($estado && !$sedeselected) {
            $filtro = true;
            $registros = RegistroConformidad::where("estado_tracking", $estado)->where(function ($query) use ($inicio, $fin) {
                $query->whereBetween('hora_de_ingreso', [$inicio, $fin])
                    ->orWhereBetween('hora_de_salida', [$inicio, $fin]);
            })->get();
        } else if (!$estado && !$sedeselected && $inicio) {
            $filtro = true;
            $registros = RegistroConformidad::whereBetween('hora_de_ingreso', [$inicio, $fin])
                ->orWhereBetween('hora_de_salida', [$inicio, $fin])->get();
        }
        return response()->json(compact('registros', "filtro", "estado", "sedeselected", "inicio", "fin"));
    }
    public function create()
    {
        $sedes = Sede::all();
        return view('registro_conformidad.crear', compact("sedes"));
    }
    public function store(Request $request)
    {
        try {
            $validation = $request->validate([
                "registro_conformidad" => "required",
                "sede" => "required"
            ]);
            try {
                $file = $request->file("registro_conformidad");
                Excel::import(new RegistroConformidadImport($request->sede), $file);
                return back()->with('success', 'Todo bien!');
            } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
                $failures = $e->failures();
                $rows = "";
                $atributos = "";
                foreach ($failures as $failure) {
                    $rows = $failure->row();
                    $atributos .=  $failure->attribute() . ",\n";
                }
                return back()->with(["error" => true, "error.row" => $rows, "error.columns" => $atributos]);
            }
        } catch (Exception $e) {
            return back()->with(["exception" => true, "exception.message" => $e->getMessage()]);
        }
    }


    public function show($id)
    {
        //
    }
    public function queryParams(Request $request)
    {
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
    }
    public function requestGuias(Request $request)
    {
        $registro_conformidad = RegistroConformidad::find($request->id);
        if ($registro_conformidad) {
            $transportista = Transportista::where("sap", $registro_conformidad->sap_transportista)->first();
            if ($transportista) {
                if ($transportista->correo) {
                    $guias_remision=explode("/",$registro_conformidad->guias_de_remision);
                    $guiasentregadas = [];
                    $archivos = [];
                    $guiaspendientes = [];
                    if ($registro_conformidad->pdf_guia_transportista) {
                        $nrguiasentregadas = explode(";", $registro_conformidad->pdf_guia_transportista);
                        $archivos = explode(";", $registro_conformidad->pdf_guia_transportista);
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
                    try {
                        $email = new PedirGuias($registro_conformidad,$guiaspendientes);
                        $allcorreosconfig=CorreoConfig::all();
                        $detinatarios=[];
                        foreach ($allcorreosconfig as $correoc){
                            array_push($detinatarios,$correoc->correo);
                        }
                        Mail::to("angelgonzalezacevedo9@gmail.com")->cc($detinatarios)->send($email);
                        return response()->json(["success" => true, "message" => "El mensaje fue correctamente enviado"]);
                    } catch (Exception $e) {
                        return response()->json(["success" => false, "message" => $e->getMessage() . " Intententelo mas tarde"]);
                    }
                }
                return response()->json(["success" => false, "message" => "El transportista no tiene correo asignado agrege uno en la seccion de transportistas"]);
            }
            return response()->json(["success" => false, "message" => "El transportista no fue encontrado revise que la ficha sap del transportista que se encuentra en este registro sea la correcta"]);
        }
        return response()->json(["success" => false, "message" => "Registro no encontrado"]);
    }
    public function destroy($id)
    {
    }
}
