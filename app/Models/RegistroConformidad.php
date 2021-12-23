<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroConformidad extends Model
{
    use HasFactory;
    public $table="registro_conformidad";
    protected $fillable = [
        "e",
        "ws",
        "hora_de_ingreso",
        "hora_de_salida",
        "estado_tracking",
        "hora_de_llegada_cliente",
        "hora_de_descarga_cliente",
        "sel",
        "guias_de_remision",
        "instrucciones_de_carga",
        "orden_de_transporte",
        "cliente",
        "destino",
        "placa_tracto",
        "sap_transportista",
        "transportista",
        "chofer",
        "entrega",
        "tipo_material",
        "inconsistencias",
        "detalle",
        "peso_teorico",
        "peso_tara",
        "peso_bruto",
        "peso_balanza",
        "diferencia_peso",
        "diferencia",
        "tolerancia",
        "pedido",
        "sede"
    ];
    public function observaciones(){
      return  $this->belongsToMany(Observacion::class,"observacion_registro_conformidad","registro_conformidad_id","observacion_id")->withPivot('cantidad','id');
    }
}
