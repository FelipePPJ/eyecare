<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Exam;
use App\Models\Package;

/**
 * Classe para consumo via API
 */
class DashboardController extends Controller
{
    /**
     * Realiza busca e contagem de registros no banco. Contagem de Exames e Pacotes
     * @param Void
     * @return Json
     */
    public function index()
    {
        return response()->json([

            // Contagem de exames ativos
            'exams' => Exam::count(),

            // Contagem de pacotes ativos
            'packages' => Package::count(),
        ]);
    }
}
