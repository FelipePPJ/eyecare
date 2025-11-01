<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    /**
     * Carregamento de tela inicial / Home do portal
     *
     * @return void
     */
    public function index()
    {
        return view('welcome');
    }
}
