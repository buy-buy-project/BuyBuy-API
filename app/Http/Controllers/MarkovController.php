<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Consumidor;
use App\Models\Produto;

use App\Helpers\Markov;
use App\Helpers\Bayes;

class MarkovController extends Controller
{

	/**
     * Método construtor
     *
     * @return void
     */
    public function __construct()
    {
        $this->consumidores = Consumidor::all();
        $this->produtos = Produto::all();
    }

    /**
     * Gera rede markoviana
     *
     * @return void
     */
    public function index()
    {
        foreach ($this->consumidores as $consumidor) {
        	foreach ($this->produtos as $produto) {
                $markov = Markov::aprendizagem($consumidor->id, $produto->id);
                Bayes::inferencia($markov['rede'], $markov['historico'], $markov['totalPorTransicao']);
        	}
        }
    }

}
