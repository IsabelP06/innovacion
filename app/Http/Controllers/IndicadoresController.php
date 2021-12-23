<?php

namespace App\Http\Controllers;
use App\Models\RegistroConformidad;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndicadoresController extends Controller
{
    public function indicadoresFormHome(Request $request)
    {
        try {
            $iniciofiltro = $request->inicio;
            $finfiltro = $request->fin;
            if ($iniciofiltro && $finfiltro) {
                $chart1 = DB::select('select estado_tracking as estado,count(id) as cantidad from registro_conformidad where hora_de_salida between ? and ? or hora_de_ingreso between ? and ? group by estado_tracking', [$iniciofiltro, $finfiltro, $iniciofiltro, $finfiltro]);
                $chart2 = DB::select('select count(orc.id) as cantidad, rc.transportista as transportista from observacion_registro_conformidad orc inner join registro_conformidad rc on rc.id=orc.registro_conformidad_id where  orc.created_at between ? and ? GROUP by rc.transportista order by cantidad desc', [$iniciofiltro, $finfiltro]);
                $chart3 = DB::select('select count(orc.id) as cantidad, ob.nombre as observacion from observacion_registro_conformidad orc inner join observacions ob on ob.id=orc.observacion_id where orc.created_at between ? and ? GROUP by ob.nombre order by cantidad desc', [$iniciofiltro, $finfiltro]);
                return response()->json(["success" => true, "chart3" => $chart3, "chart2" => $chart2, "chart1" => $chart1]);
            } else {
                return response()->json(["success" => false, "message" => "Error", "reas" => $request->all()]);
            }
        } catch (Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()]);
        }
    }
}
