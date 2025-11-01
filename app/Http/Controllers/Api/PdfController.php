<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

/**
 * Classe responsável por receber requisição com montagem de solicitação de exames
 */
class PdfController extends Controller
{
    public function export(Request $request)
    {
        // Obtém todos os boxes submetidos
        $boxes = collect($request->input('boxes', []));

        // Agrupa tudo por grupo (menos os individuais)
        $grouped = [];
        $individuals = collect();

        // Realiza loop de boxes para iniciar segmentação
        foreach ($boxes as $key => $box) {
            foreach ($box['exams'] as $exam) {
                if (!empty($exam['is_individual'])) {
                    // Exame individual vai em sua própria página
                    $individuals->push([
                        'box_type' => $box['type'],
                        'box_name' => $box['name'] ?? null,
                        'observations' => $box['observations'] ?? '',
                        'exam' => $exam
                    ]);
                } else {
                    // Exames agrupados pelo group_id
                    $groupId = $exam['group_id'] ?? 'sem_grupo';
                    $grouped[$groupId][$key][] = [
                        'box_type' => $box['type'],
                        'box_name' => $box['name'] ?? null,
                        'observations' => $box['observations'] ?? '',
                        'exam' => $exam
                    ];
                }
            }
        }

        // Remove grupos vazios
        $grouped = collect($grouped)->filter(function ($exams) {
            return !empty($exams);
        });

        // Inicializa PDF com parâmetros segmentados
        $pdf = Pdf::loadView('pdf', [
            'grouped' => $grouped,
            'individuals' => $individuals,
        ]);

        // Realiza render direto na tela
        return $pdf->stream('solicitacao.pdf');
    }
}
