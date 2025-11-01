<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExamsSeeder extends Seeder
{
    /**
     * Seed base para relação de grupos associativos a exames e pacotes
     */
    public function run(): void
    {
        $exames = [
            // 👁️ Exames de vista
            [
                'name' => 'Acuidade Visual',
                'laterality' => 'AO',
                'comment' => 'Avalia a capacidade de distinguir detalhes de objetos a diferentes distâncias.'
            ],
            [
                'name' => 'Teste de Refração',
                'laterality' => 'AO',
                'comment' => 'Determina o grau de miopia, hipermetropia ou astigmatismo para prescrição de lentes corretivas.'
            ],
            [
                'name' => 'Tonometria',
                'laterality' => 'AO',
                'comment' => 'Mede a pressão intraocular para detectar risco de glaucoma.'
            ],
            [
                'name' => 'Fundoscopia (Exame de Fundo de Olho)',
                'laterality' => 'AO',
                'comment' => 'Avalia a retina, nervo óptico e vasos sanguíneos internos do olho.'
            ],
            [
                'name' => 'Campimetria Visual',
                'laterality' => 'AO',
                'comment' => 'Examina o campo visual periférico para detectar alterações em doenças como o glaucoma.'
            ],

            // 🧪 Exames gerais
            [
                'name' => 'Hemograma Completo',
                'laterality' => null,
                'comment' => 'Avalia a quantidade e qualidade das células do sangue, útil para detectar infecções e anemias.'
            ],
            [
                'name' => 'Glicemia em Jejum',
                'laterality' => null,
                'comment' => 'Mede o nível de glicose no sangue após jejum, usado para diagnóstico de diabetes.'
            ],
            [
                'name' => 'Colesterol Total e Frações',
                'laterality' => null,
                'comment' => 'Avalia os níveis de colesterol total, HDL e LDL, importante para controle cardiovascular.'
            ],
            [
                'name' => 'Eletrocardiograma (ECG)',
                'laterality' => null,
                'comment' => 'Registra a atividade elétrica do coração para detectar arritmias e outras anormalidades cardíacas.'
            ],
            [
                'name' => 'Urina Tipo I (EAS)',
                'laterality' => null,
                'comment' => 'Exame simples de urina usado para avaliar infecções urinárias e função renal.'
            ]
        ];


        foreach ($exames as $key => $exame) {
            DB::table('exams')->updateOrInsert(
                [
                    'id' => ($key + 1)
                ],
                [
                    'name' => trim(strip_tags($exame['name'])),
                    'group_id' => 1,
                    'laterality' => $exame['laterality'],
                    'comment' => trim(strip_tags($exame['comment'])),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
