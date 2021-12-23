<?php

namespace App\Imports;

use App\Models\RegistroConformidad;
use DateTime;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use function PHPUnit\Framework\isEmpty;

class RegistroConformidadImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public $sede;
    public function __construct($sede)
    {
        $this->sede = $sede;
    }
    public function uniqueBy()
    {
        return 'orden_de_transporte';
    }
    public function formtDate($date)
    {
        if(!$date)return null;
        $array1 = explode(" ", trim($date));
        if (count($array1)>1) {
            $fecha = explode("/", $array1[0]);
            if (count($fecha)>2) {
                $hora = $array1[1] . ":00";
                return  new DateTime($fecha[2] . "-" . $fecha[1] . "-" . $fecha[0] . " " . $hora);
            }
            return null;
        } 
        return null;
    }
    public function model(array $row)
    {
        $hi = $this->formtDate($row["hora_de_ingreso"]);
        $hs = $this->formtDate($row["hora_de_salida"]);
        $modelounico = RegistroConformidad::where("orden_de_transporte", $row["orden_de_transporte"])->get();
        if (count($modelounico)) {
            return null;
        }
        $estado="PENDIENTE";        
        if (strlen($row["sap_transportista"])<4) {
            $estado="OK";
        }
        return new RegistroConformidad([
            "e" => $row["e"],
            "ws" => $row["ws"],
            "hora_de_ingreso" => $hi,
            "hora_de_salida" => $hs,
            "estado_tracking" => $estado,
            "hora_de_llegada_cliente" => strtotime($row["hora_de_llegada_cliente"]) ? $this->formtDate($row["hora_de_llegada_cliente"]) : null,
            "hora_de_descarga_cliente" => strtotime($row["hora_de_descarga_cliente"]) ? $this->formtDate($row["hora_de_descarga_cliente"]) : null,
            "sel" => $row["sel"],
            "guias_de_remision" => $row["guias_de_remision"],
            "instrucciones_de_carga" => $row["instrucciones_de_carga"],
            "orden_de_transporte" => $row["orden_de_transporte"],
            "cliente" => $row["cliente"],
            "destino" => $row["destino"],
            "placa_tracto" => $row["placa_tracto"],
            "sap_transportista" => $row["sap_transportista"],
            "transportista" => $row["transportista"],
            "chofer" => $row["chofer"],
            "entrega" => $row["entrega"],
            "tipo_material" => $row["tipo_material"],
            "inconsistencias" => $row["inconsistencias"],
            "detalle" => $row["detalle"],
            "peso_teorico" => $row["peso_teorico"],
            "peso_tara" => $row["peso_tara"],
            "peso_bruto" => $row["peso_bruto"],
            "peso_balanza" => $row["peso_balanza"],
            "diferencia_peso" => $row["diferencia_peso"],
            "diferencia" => $row["diferencia"],
            "tolerancia" => $row["tolerancia"],
            "pedido" => $row["pedido"],
            "sede" => $this->sede ? $this->sede : "POR RESOLVER"
        ]);
    }
    public function rules(): array
    {
        return [
            'sap_transportista' => 'required',
            'orden_de_transporte' => 'required',
            'hora_de_ingreso' => 'required|date',
            'hora_de_salida' => 'required|date',
            'transportista' => 'required',
            'guias_de_remision'=> 'required',
            ];
    }
}
