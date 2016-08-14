<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListaCompraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lista_compra', function (Blueprint $table) {
            $table->increments('id');
            $table->date('data_lista');
            $table->tinyInteger('recomendada')->default(0);
            $table->tinyInteger('confirmada')->default(0);
            $table->timestamps();
        });

        Schema::table('lista_compra', function (Blueprint $table) {
            $table->integer('consumidor_id')
                ->unsigned()
                ->after('confirmada');
                
            $table->foreign('consumidor_id')->references('id')->on('consumidor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lista_compra', function (Blueprint $table) {
            $table->dropForeign('lista_compra_consumidor_id_foreign');
            $table->dropColumn('consumidor_id');            
        });

        Schema::drop('lista_compra');
    }
}
