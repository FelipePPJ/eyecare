<?php

namespace App\Http\Controllers;

class RequestController extends Controller
{
    /**
     * Carregamento de tela de Solicitações de Exames
     *
     * @return void
     */
    public function index()
    {
        return view('requests');
    }
}
