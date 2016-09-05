<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::resource('consumidor', 'ConsumidorController');

Route::resource('produto', 'ProdutoController');

Route::resource('compra', 'CompraController');

Route::resource('listaCompra', 'ListaCompraController');

Route::get('markov', 'MarkovController@index');

Route::get('historico/{idConsumidor}', 'HistoricoController@index');

Route::group(['prefix' => 'experimentos'], function () {
	Route::get('experimento1', 'ExperimentosController@experimento1');
	Route::get('experimento2', 'ExperimentosController@experimento2');
});

