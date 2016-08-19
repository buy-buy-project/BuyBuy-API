<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $table = 'compra';
    protected $fillable = ['quantidade', 'produto_id', 'lista_id'];

    public function listaCompra() {
        return $this->belongsTo('App\Models\ListaCompra');
    }

    public function listaConsumidor() {
    	return $this->listaCompra()->where('consumidor_id', 1);
    }
}

