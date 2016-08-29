<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $table = 'compra';
    protected $fillable = ['quantidade', 'produto_id', 'lista_id'];
    public $timestamps = false;

    public function listaCompra() {
        return $this->belongsTo('App\Models\ListaCompra');
    }
}