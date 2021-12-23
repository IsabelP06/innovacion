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
use ObservacionRegistroConformidad;
class RegistroConformidadTransportistaAppController extends Controller
{
    public function index(Request $request)
    {
        $registros = [];
        $filtro = false;
        $inicio = $request->inicio;
        $fin = $request->fin;
        $sap_trasportista = $request->sap_trasportista;
        $estado = $request->estado;
        if ($estado) {
            $filtro = true;
            $registros = DB::select('select * from registro_conformidad where sap_transportista=? and estado_tracking=?  and (hora_de_ingreso between ? and ? or hora_de_salida between ? and ?)', [$sap_trasportista, $estado, $inicio, $fin, $inicio, $fin]);
            if (!$registros) $registros = [];
        } else if (!$estado && $inicio) {
            $filtro = true;
            $registros = DB::select('select * from registro_conformidad where sap_transportista=? and (hora_de_ingreso between ? and ? or hora_de_salida between ? and ?)', [$sap_trasportista, $inicio, $fin, $inicio, $fin]);
            if (!$registros) $registros = [];
        }
        $success = true;
        return response()->json(compact('success', 'registros', "filtro", "estado", "inicio", "fin"));
    }
    public function archivos(Request $request, $id)
    {
        $registro = RegistroConformidad::find($id);
        return response()->json(compact("registro"));
    }
    public function observaciones(Request $request, $id)
    {
        $observaciones = Observacion::all();

        $registro = RegistroConformidad::find($id);
        $registroobservaciones = DB::select('select orc.*,o.nombre as nombre from observacion_registro_conformidad orc inner join observacions o on o.id=orc.observacion_id  where registro_conformidad_id=?', [$id]);
        !$registroobservaciones ? $registroobservaciones = [] : null;
        $registro["observations"] = $registroobservaciones;
        return response()->json(compact("registro", "observaciones"));
    }
    public function archivoStore(Request $request)
    {
        try {
            if(!$request->guia_transportista || !$request->guia_cobranza){
                return response()->json(["success" => false, "message" => "Debe cargar ambos archivos"]);
            }
            $file_transportista = $request->file("guia_transportista");
            $registro = RegistroConformidad::findOrFail($request->id);
            $link1 = "";
            $archivo_transportista_path = random_int(1, 10000000). "_transportista_" . $request->guia_de_remision . '_.' . $request->file("guia_transportista")->getClientOriginalExtension();
            Storage::putFileAs("/" . $registro->sap_transportista, $file_transportista, $archivo_transportista_path);
            $tempvalue1= "/pdf_guias/" . $registro->sap_transportista . "/" . $archivo_transportista_path.";".$registro->pdf_guia_transportista;
            if(!$registro->pdf_guia_transportista){
                $tempvalue1= "/pdf_guias/" . $registro->sap_transportista . "/" . $archivo_transportista_path;
            }
            $registro->pdf_guia_transportista = $tempvalue1;
            $link1 = "/pdf_guias/" . $registro->sap_transportista . "/" . $archivo_transportista_path;
            $link2 = "";
            $archivo_cobranza = $request->file("guia_cobranza");
            $archivo_cobranza_path = random_int(1, 10000000) . "_cobranza_". $request->guia_de_remision . '_.' . $request->file("guia_cobranza")->getClientOriginalExtension();
            Storage::putFileAs("/" . $registro->sap_transportista, $archivo_cobranza, $archivo_cobranza_path);
            $tempvalue2="/pdf_guias/" . $registro->sap_transportista . "/" . $archivo_cobranza_path .";". $registro->pdf_guia_cobranza;      
            if(!$registro->pdf_guia_cobranza){
                $tempvalue2="/pdf_guias/" . $registro->sap_transportista . "/" . $archivo_cobranza_path;
            }
            $registro->pdf_guia_cobranza =$tempvalue2; 
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
                $correoenvio = new EntregaGuias($registro, public_path() . $link2,public_path(). $link1,$request->guia_de_remision);
                Mail::to($detinatarios[0])->cc($detinatarios)->send($correoenvio);
                return response()->json(["success" => true, "message" => "Archivos cargados correctamente y correo enviado"]);
            } catch (Exception $ex) {
                return response()->json(["success" => true, "message" => "Archivos cargados correctamente pero no se puedo enviar el correo hagalo manualmente, detalle error  : " . $ex->getMessage()]);
            }
        } catch (Exception $e) {
            return response()->json(["success" => false, "message" => "Error al cargar archivos : " . $e->getMessage()]);
        }
    }
    public function archivoStoreUpdate(Request $request)
    {
        try {
            if(!$request->guia_transportista || !$request->guia_cobranza){
                return response()->json(["success" => false, "message" => "Debe cargar ambos archivos"]);
            }
            $archivo_transportista = $request->file("guia_transportista");
            $registro = RegistroConformidad::findOrFail($request->id);
            $nvlink="";
            if ($registro->pdf_guia_transportista) {
                $archivosubidos=explode(";",$registro->pdf_guia_transportista);
                foreach ($archivosubidos as $archivo) {
                    $nrguia=explode("_",$archivo)[3];
                    if($nrguia==$request->guia_de_remision){
                        Storage::delete([$archivo]);
                    }else{
                        if($nvlink){
                            $nvlink.=";".$archivo;
                        }else{
                            $nvlink=$archivo;
                        }
                    }
                }
            }
            $registro->pdf_guia_transportista=$nvlink;
            $link1 = "";
            $archivo_transportista_path = random_int(1, 10000000). "_transportista_" . $request->guia_de_remision . '_.' . $request->file("guia_transportista")->getClientOriginalExtension();
            Storage::putFileAs("/" . $registro->sap_transportista, $archivo_transportista, $archivo_transportista_path);
            $tempvalue1= "/pdf_guias/" . $registro->sap_transportista . "/" . $archivo_transportista_path.";".$registro->pdf_guia_transportista;
            if(!$registro->pdf_guia_transportista){
                $tempvalue1= "/pdf_guias/" . $registro->sap_transportista . "/" . $archivo_transportista_path;
            }
            $registro->pdf_guia_transportista = $tempvalue1;
            $link1 = "/pdf_guias/" . $registro->sap_transportista . "/" . $archivo_transportista_path;
            $link2 = "";
            $archivo_cobranza = $request->file("guia_cobranza");
            $nvlink2="";
            if ($registro->pdf_guia_cobranza) {
                $archivosubidos2=explode(";",$registro->pdf_guia_cobranza);
                foreach ($archivosubidos2 as $archivoo) {
                    $nrguia=explode("_",$archivoo)[3];
                    if($nrguia==$request->guia_de_remision){
                        Storage::delete([$archivoo]);
                    }else{
                        if($nvlink2){
                            $nvlink2.=";".$archivoo;
                        }else{
                            $nvlink2=$archivoo;
                        }
                    }
                }
            }
            $registro->pdf_guia_cobranza = $nvlink2;
            $archivo_cobranza_path = random_int(1, 10000000) . "_cobranza_". $request->guia_de_remision . '_.' . $request->file("guia_cobranza")->getClientOriginalExtension();
            Storage::putFileAs("/" . $registro->sap_transportista, $archivo_cobranza, $archivo_cobranza_path);
            $tempvalue2="/pdf_guias/" . $registro->sap_transportista . "/" . $archivo_cobranza_path .";". $registro->pdf_guia_cobranza;      
            if(!$registro->pdf_guia_cobranza){
                $tempvalue2="/pdf_guias/" . $registro->sap_transportista . "/" . $archivo_cobranza_path;
            }
            $registro->pdf_guia_cobranza =$tempvalue2; 
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
                $correoenvio = new ModificacionEntregaArchivos($registro, public_path() . $link2,public_path(). $link1,$request->guia_de_remision);
                Mail::to($detinatarios[0])->cc($detinatarios)->send($correoenvio);
                return response()->json(["success" => true, "message" => "Archivos cargados correctamente y correo enviado"]);
            } catch (Exception $ex) {
                return response()->json(["success" => true, "message" => "Archivos cargados correctamente pero no se puedo enviar el correo hagalo manualmente, detalle error  : " . $ex->getMessage()]);
            }
        } catch (Exception $e) {
            return response()->json(["success" => false, "message" => "Error : " . $e->getMessage()]);
        }
    }
    public function observacionStore(Request $request)
    {
        if (!$request->observacion_id) {
            return response()->json(["success" => false, "message" => "No se ha ingresado ninguna observación"]);
        }
        $newobservacionregistro = new ObservacionRegistro();
        $newobservacionregistro->registro_conformidad_id = $request->registro_conformidad_id;
        $newobservacionregistro->observacion_id = $request->observacion_id;
        $newobservacionregistro->cantidad = $request->cantidad;
        $save = $newobservacionregistro->save();
        if ($save) {
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
            $observacion = ObservacionRegistro::orderBy('id', 'desc')->first();
            return response()->json(["success" => true, "message" => "Observación agregada correctamente", "data" => $observacion]);
        } else {
            return response()->json(["success" => false, "message" => "Error al agregar observación"]);
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
        $registro = RegistroConformidad::find($id);
        $success = true;
        return response()->json(compact("success", "registro"));
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
    public function delete($id)
    {
        try {
            $o_registro = ObservacionRegistro::find($id);
            $registro = RegistroConformidad::find($o_registro->registro_conformidad_id);
            if (count($registro->observaciones) == 1) {
                $registro->estado_tracking = "OK";
                $registro->save();
            }
            $o_registro->delete();
            return response()->json(["success" => true, "message" => "Observación eliminada correctamente"]);
        } catch (Exception $e) {
            return response()->json(["success" => false, "message" => "Error" . $e->getMessage()]);
        }
    }
    public function destroy($id)
    {
        //
    }
}
