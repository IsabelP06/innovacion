<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistroConformidad extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registro_conformidad', function (Blueprint $table) {
            $table->id();
            $table->string("e")->nullable();
            $table->string("ws")->nullable();
            $table->dateTime("hora_de_ingreso");
            $table->dateTime("hora_de_salida");
            $table->string("estado_tracking");
            $table->dateTime("hora_de_llegada_cliente")->nullable();
            $table->dateTime("hora_de_descarga_cliente")->nullable();
            $table->string("sel")->nullable();
            $table->string("guias_de_remision")->nullable();
            $table->string("instrucciones_de_carga")->nullable();
            $table->string("orden_de_transporte")->unique();
            $table->string("cliente")->nullable();
            $table->string("destino")->nullable();
            $table->string("placa_tracto")->nullable();
            $table->string("sap_transportista");
            $table->string("transportista")->nullable();
            $table->string("chofer")->nullable();
            $table->string("entrega")->nullable();
            $table->string("tipo_material")->nullable();
            $table->string("inconsistencias")->nullable();
            $table->string("detalle")->nullable();
            $table->string("peso_teorico")->nullable();
            $table->string("peso_tara")->nullable();
            $table->string("peso_bruto")->nullable();
            $table->string("peso_balanza")->nullable();
            $table->string("diferencia_peso")->nullable();
            $table->string("diferencia")->nullable();
            $table->string("tolerancia")->nullable();
            $table->string("pedido")->nullable();
            $table->text("pdf_guia_transportista")->nullable();
            $table->text("pdf_guia_cobranza")->nullable();
            $table->string("sede");
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('registro_conformidad');
    }
}
