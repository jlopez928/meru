<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();

            $table->string('nombre', 150);
            $table->integer('padre');
            $table->integer('orden');
            $table->boolean('activo');
            $table->string('modulo', 30);
            $table->string('url_destino', 200);
            $table->string('id_aplicacion', 20);
            $table->string('icono', 50);
            $table->string('descripcion', 200)->nullable();

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
        Schema::dropIfExists('menus');
    }
};
