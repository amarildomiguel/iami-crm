<?php

return [
    'importers' => [
        'persons' => [
            'title' => 'Pessoas Singulares',

            'validation' => [
                'errors' => [
                    'duplicate-email' => 'E-mail: \'%s\' aparece mais de uma vez no ficheiro de importação.',
                    'duplicate-phone' => 'Telemóvel: \'%s\' aparece mais de uma vez no ficheiro de importação.',
                    'email-not-found' => 'E-mail: \'%s\' não foi encontrado no sistema.',
                ],
            ],
        ],

        'products' => [
            'title' => 'Serviços Jurídicos',

            'validation' => [
                'errors' => [
                    'sku-not-found' => 'Serviço com este código não foi encontrado.',
                ],
            ],
        ],

        'leads' => [
            'title' => 'Processos',

            'validation' => [
                'errors' => [
                    'id-not-found' => 'ID: \'%s\' não foi encontrado no sistema.',
                ],
            ],
        ],
    ],

    'validation' => [
        'errors' => [
            'column-empty-headers' => 'As colunas número "%s" têm cabeçalhos vazios.',
            'column-name-invalid'  => 'Nomes de colunas inválidos: "%s".',
            'column-not-found'     => 'Colunas obrigatórias não encontradas: %s.',
            'column-numbers'       => 'O número de colunas não corresponde ao número de linhas no cabeçalho.',
            'invalid-attribute'    => 'O cabeçalho contém atributo(s) inválido(s): "%s".',
            'system'               => 'Ocorreu um erro inesperado no sistema.',
            'wrong-quotes'         => 'Foram utilizadas aspas curvas em vez de aspas rectas.',
            'already-exists'       => 'O campo :attribute já existe.',
        ],
    ],
];
