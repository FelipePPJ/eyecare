<?php

/*
|--------------------------------------------------------------------------
| Pagination Language Lines
|--------------------------------------------------------------------------
|
| The following language lines are used by the paginator library to build
| the simple pagination links. You are free to change them to anything
| you want to customize your views to better match your application.
|
*/

return [

    'menu' => [
        'home' => 'Home',
        'exams' => 'Exames',
        'packages' => 'Pacotes de Exames',
        'request' => 'Solicitar',
    ],

    'OD' => 'Olho direito',
    'OE' => 'Olho esquerdo',
    'AO' => 'Ambos os olhos',

    'loading' => 'Carregando..',

    'footer' => '- Controle de exames.',

    'pdf' => [
        'title' => 'Solicitação de Exames',
        'age' => 'Idade',
        'sex' => 'Sexo',
        'medical_record_number' => 'Nº Prontuário',
        'date' => 'Data',
        'performed_by' => 'Realizado por',
        'patient' => 'Paciente',
        'company' => 'Convênio',
    ],

    'dashboard' => [
        'resume' => 'Resumo',
    ],

    'form' => [
        'select' => 'Selecione',
        'cancel' => 'Cancelar',
        'save' => 'Salvar',
        'actions' => 'Ações',
        'edit' => 'Editar',
        'remove' => 'Remover',
        'observations' => 'Observações',
        'name' => 'Nome',
        'observations_abrev' => 'Obs',
        'export' => 'Exportar',
    ],

    'exams' => [
        'title' => 'Exames',
        'new' => 'Novo Exame',
        'edit' => 'Editar Exame',
        'group' => 'Grupo',
        'laterality' => 'Lateralidade',
        'comment' => 'Comentário',

        'create' => [
            'success' => 'Exame cadastrado com sucesso!',
            'invalid' => 'Não foi possível validar os campos!',
            'error' => 'Ocorreu um erro ao cadastrar o exame.',

            'validation' => [
                'name' => 'O nome do exame é obrigatório.',
                'group_id_required' => 'O grupo é obrigatório.',
                'group_id_exists' => 'O grupo informado não existe.',
                'laterality' => 'Uma lateralidade deve ser escolhida.'
            ]
        ],

        'update' => [
            'success' => 'Exame atualizado com sucesso!',
            'invalid' => 'Não foi possível validar os campos!',
            'notfound' => 'O exame não pode ser localizado.',
            'error' => 'Ocorreu um erro ao atualizar o exame.',

            'validation' => [
                'name' => 'O nome do exame é obrigatório.',
                'group_id_required' => 'O grupo é obrigatório.',
                'group_id_exists' => 'O grupo informado não existe.',
                'laterality' => 'Uma lateralidade deve ser escolhida.'
            ]
        ],
    ],

    'packages' => [
        'title' => 'Pacotes de Exames',
        'new' => 'Novo Pacote',
        'edit' => 'Editar Pacote',
        'group' => 'Grupo',
        'laterality' => 'Lateralidade',
        'comment' => 'Comentário',
        'exams_count' => 'Exames associados',
        'exams' => 'Exames associados',
        'add_exam' => 'Adicionar Exame',

        'create' => [
            'success' => 'Pacote cadastrado com sucesso!',
            'invalid' => 'Não foi possível validar os campos!',
            'error' => 'Ocorreu um erro ao cadastrar pacote.',

            'validation' => [
                'name' => 'O nome do pacote é obrigatório.',
                'exams_required' => 'Ao menos um exame deve ser selecionado',
                'exam_exists' => 'Exame selecionado inválido',
                'group_id_required' => 'O grupo é obrigatório.',
                'group_id_exists' => 'O grupo informado não existe.',
                'laterality' => 'Uma lateralidade deve ser escolhida.'
            ]
        ],

        'update' => [
            'success' => 'Exame atualizado com sucesso!',
            'invalid' => 'Não foi possível validar os campos!',
            'notfound' => 'O exame não pode ser localizado.',
            'error' => 'Ocorreu um erro ao atualizar o exame.',

            'validation' => [
                'name' => 'O nome do pacote é obrigatório.',
                'exams_required' => 'Ao menos um exame deve ser selecionado',
                'exam_exists' => 'Exame selecionado inválido',
                'group_id_required' => 'O grupo é obrigatório.',
                'group_id_exists' => 'O grupo informado não existe.',
                'laterality' => 'Uma lateralidade deve ser escolhida.'
            ]
        ],
    ],

    'requests' => [
        'title' => 'Solicitação de Exames',
        'new_packages' => 'Incluir Pacote',
        'new_exams' => 'Incluir Exames Avulsos',
        'exams' => 'Exames Avulsos',
        'general_group' => 'Grupo Geral',
        'clean_standalone' => 'Limpar avulsos',
        'clean_package' => 'Remove pacote',
    ],

    'errors' => [
        'validation' => 'Ocorreu um erro ao validar os dados',
        'save' => 'Houve um erro ao salvar os dados',
        'other' => 'Erro inesperado. Tente novamente',
        'pdf_export_nothing' => 'Nenhum exame para exportar.',
        'pdf_generate' => 'Falha ao gerar PDF.',
        'pdf_erro' => 'Erro ao gerar PDF.',
    ]
];
