<?php

namespace App\Http\Controllers;

class ExamsController extends Controller
{
    /**
     * Carregamento de tela de exames
     *
     * @return void
     */
    public function index()
    {
        return view('exams');
    }
}
