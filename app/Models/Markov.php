<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Compra;

class Markov extends Model
{
    public static function comprasDoConsumidorPorProduto($idConsumidor, $idProduto) {
        $data365dias = date('Y-m-d', strtotime("-365 days"));

        $compras = Compra::with('listaCompra')
            ->join('lista_compra', 'lista_compra.id', '=', 'compra.lista_compra_id')
            ->where('lista_compra.consumidor_id', $idConsumidor)
            ->where('lista_compra.data_lista', '>=', $data365dias)
            ->where('lista_compra.confirmada', '=', 1)
            ->where('compra.produto_id', '=', $idProduto)
            ->orderBy('lista_compra.data_lista')
            ->get();

        return $compras;
    } 
}
