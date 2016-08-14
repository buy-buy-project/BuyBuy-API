<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compra', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quantidade');
            $table->timestamps();
        });

        Schema::table('compra', function (Blueprint $table) {
            $table->integer('lista_id')
                ->unsigned()
                ->after('quantidade');

            $table->integer('produto_id')
                ->unsigned()
                ->after('quantidade');
                
            $table->foreign('lista_id')->references('id')->on('lista_compra');
            $table->foreign('produto_id')->references('id')->on('produto');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('compra', function (Blueprint $table) {
            $table->dropForeign('compra_lista_id_foreign');
            $table->dropColumn('lista_id');
            $table->dropForeign('compra_produto_id_foreign');
            $table->dropColumn('produto_id');
        });

        Schema::drop('compra');
    }
}
