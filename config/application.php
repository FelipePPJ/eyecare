<?php

/**
 * Configurações base da aplicação associadas ao negócio
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Groups de impressão / Exames
    |--------------------------------------------------------------------------
    |
    | Relação de grupos de impressão. São associados aos exames e ao seu relacionamento
    | com pacotes. Tem a finalidade de especificar tipos/categorias possíveis que os exames
    | podem ter no momento de serem solicitados.
    | Lateralidade do exame: OD - Olho direito, OE - Olho esquerdo, AO - Ambos os olhos
    |
    */

    'laterality' => ['OD', 'OE', 'AO'],
];
