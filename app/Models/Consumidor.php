<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consumidor extends Model
{
	protected $table = 'consumidor';
    protected $fillable = ['nome'];

    public function listasCompra() {
        return $this->hasMany('App\Models\ListaCompra');
    }
}
