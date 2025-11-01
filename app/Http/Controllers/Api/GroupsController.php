<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Group;

/**
 * Classe para consumo via API
 */
class GroupsController extends Controller
{
    /**
     * Realiza busca e contagem de registros no banco. Contagem de Exames e Pacotes
     * @param Void
     * @return Json
     */
    public function index()
    {
        return response()->json(Group::all());
    }
}
