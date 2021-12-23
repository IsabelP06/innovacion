<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ObservacionRegistroConformidad extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('observacion_registro_conformidad', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registro_conformidad_id')
                ->constrained("registro_conformidad")
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('observacion_id')
                ->constrained("observacions")
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string("cantidad");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('observacion_registro_conformidad');
    }
}
