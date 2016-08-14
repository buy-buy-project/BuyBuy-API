<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListaCompra extends Model
{
    protected $table = 'lista_compra';
    protected $fillable = ['data_lista', 'recomendada', 'confirmada', 'consumidor_id'];
}
