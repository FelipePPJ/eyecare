<?php

namespace App\Http\Controllers;

class PackagesController extends Controller
{
    /**
     * Carregamento de tela Pacotes de exames
     *
     * @return void
     */
    public function index()
    {
        return view('packages');
    }
}
