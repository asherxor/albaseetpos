<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Verificar si la tabla ya existe antes de intentar crearla
        if (!Schema::hasTable('tazas')) {
            Schema::create('tazas', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedInteger('business_id');
                $table->unsignedInteger('currency_id');
                $table->decimal('value', 50, 15);
                $table->timestamps();
                $table->string('alias')->nullable();
                $table->foreign('business_id')->references('id')->on('business')->onDelete('cascade');
                $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');
            });
        } else {
            // Si la tabla ya existe, podemos verificar y agregar columnas faltantes
            Schema::table('tazas', function (Blueprint $table) {
                // Verificar y agregar la columna 'alias' si no existe
                if (!Schema::hasColumn('tazas', 'alias')) {
                    $table->string('alias')->nullable();
                }
                // Puedes agregar más condiciones para otras columnas que puedan faltar
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       /* 
        Schema::dropIfExists('tazas');*/
    }
};
