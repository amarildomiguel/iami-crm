<?php

return [
    'seeders' => [
        'attributes' => [
            'leads' => [
                'description'         => 'Descrição',
                'expected-close-date' => 'Data de Encerramento Prevista',
                'lead-value'          => 'Valor do Processo',
                'sales-owner'         => 'Advogado Responsável',
                'source'              => 'Origem do Caso',
                'title'               => 'Título',
                'type'                => 'Tipo',
                'pipeline'            => 'Fluxo Processual',
                'stage'               => 'Fase Processual',
            ],

            'persons' => [
                'contact-numbers' => 'Números de Contacto',
                'emails'          => 'E-mails',
                'job-title'       => 'Cargo',
                'name'            => 'Nome',
                'organization'    => 'Organização',
                'sales-owner'     => 'Advogado Responsável',
            ],

            'organizations' => [
                'address'     => 'Morada',
                'name'        => 'Nome',
                'sales-owner' => 'Advogado Responsável',
            ],

            'products' => [
                'description' => 'Descrição',
                'name'        => 'Nome',
                'price'       => 'Preço (Kz)',
                'quantity'    => 'Quantidade',
                'sku'         => 'Código',
            ],

            'quotes' => [
                'adjustment-amount' => 'Valor de Ajustamento',
                'billing-address'   => 'Morada de Facturação',
                'description'       => 'Descrição',
                'discount-amount'   => 'Valor do Desconto',
                'discount-percent'  => 'Percentagem de Desconto',
                'expired-at'        => 'Válido até',
                'grand-total'       => 'Total Geral (Kz)',
                'person'            => 'Cliente',
                'sales-owner'       => 'Advogado Responsável',
                'shipping-address'  => 'Morada de Entrega',
                'sub-total'         => 'Subtotal (Kz)',
                'subject'           => 'Assunto',
                'tax-amount'        => 'Valor do IVA (14%)',
            ],

            'warehouses' => [
                'contact-address' => 'Morada de Contacto',
                'contact-emails'  => 'E-mails de Contacto',
                'contact-name'    => 'Nome do Contacto',
                'contact-numbers' => 'Números de Contacto',
                'description'     => 'Descrição',
                'name'            => 'Nome',
            ],
        ],

        'email' => [
            'activity-created'      => 'Diligência Adicionada',
            'activity-modified'     => 'Diligência Modificada',
            'date'                  => 'Data',
            'new-activity'          => 'Tem uma nova diligência, veja os detalhes abaixo',
            'new-activity-modified' => 'Uma diligência foi modificada, veja os detalhes abaixo',
            'participants'          => 'Participantes',
            'title'                 => 'Título',
            'type'                  => 'Tipo',
        ],

        'lead' => [
            'pipeline' => [
                'default' => 'Fluxo Processual Padrão',

                'pipeline-stages' => [
                    'follow-up'   => 'Acompanhamento',
                    'lost'        => 'Encerrado — Perdido',
                    'negotiation' => 'Negociação / Acordo',
                    'new'         => 'Consulta Inicial',
                    'prospect'    => 'Processo em Curso',
                    'won'         => 'Encerrado — Ganho',
                ],
            ],

            'source' => [
                'direct'   => 'Directo',
                'email'    => 'E-mail',
                'phone'    => 'Telemóvel',
                'web'      => 'Web',
                'web-form' => 'Formulário de Consulta',
            ],

            'type' => [
                'existing-business' => 'Cliente Existente',
                'new-business'      => 'Novo Cliente',
            ],
        ],

        'user' => [
            'role' => [
                'administrator-role' => 'Perfil de Administrador',
                'administrator'      => 'Administrador',
            ],
        ],

        'workflow' => [
            'email-to-participants-after-activity-updation' => 'E-mail para participantes após actualização de diligência',
            'email-to-participants-after-activity-creation' => 'E-mail para participantes após adição de diligência',
        ],
    ],

    'installer' => [
        'index' => [
            'create-administrator' => [
                'admin'            => 'Administrador',
                'krayin'           => 'IAMI CRM',
                'confirm-password' => 'Confirmar Palavra-passe',
                'email'            => 'E-mail',
                'email-address'    => 'admin@escritorio.ao',
                'password'         => 'Palavra-passe',
                'title'            => 'Adicionar Administrador',
            ],

            'environment-configuration' => [
                'algerian-dinar'              => 'Dinar Argelino (DZD)',
                'allowed-currencies'          => 'Moedas Permitidas',
                'allowed-locales'             => 'Idiomas Permitidos',
                'application-name'            => 'Nome da Aplicação',
                'argentine-peso'              => 'Peso Argentino (ARS)',
                'australian-dollar'           => 'Dólar Australiano (AUD)',
                'krayin'                      => 'IAMI CRM',
                'bangladeshi-taka'            => 'Taka do Bangladesh (BDT)',
                'brazilian-real'              => 'Real Brasileiro (BRL)',
                'british-pound-sterling'      => 'Libra Esterlina (GBP)',
                'canadian-dollar'             => 'Dólar Canadiano (CAD)',
                'cfa-franc-bceao'             => 'Franco CFA BCEAO (XOF)',
                'cfa-franc-beac'              => 'Franco CFA BEAC (XAF)',
                'chilean-peso'                => 'Peso Chileno (CLP)',
                'chinese-yuan'                => 'Yuan Chinês (CNY)',
                'colombian-peso'              => 'Peso Colombiano (COP)',
                'czech-koruna'                => 'Coroa Checa (CZK)',
                'danish-krone'                => 'Coroa Dinamarquesa (DKK)',
                'database-connection'         => 'Ligação à Base de Dados',
                'database-hostname'           => 'Hostname da Base de Dados',
                'database-name'               => 'Nome da Base de Dados',
                'database-password'           => 'Palavra-passe da Base de Dados',
                'database-port'               => 'Porta da Base de Dados',
                'database-prefix'             => 'Prefixo da Base de Dados',
                'database-username'           => 'Utilizador da Base de Dados',
                'default-currency'            => 'Moeda Padrão',
                'default-locale'              => 'Idioma Padrão',
                'default-timezone'            => 'Fuso Horário Padrão',
                'default-url'                 => 'URL Padrão',
                'default-url-link'            => 'https://localhost',
                'euro'                        => 'Euro (EUR)',
                'angolan-kwanza'              => 'Kwanza Angolano (AOA)',
                'mysql'                       => 'MySQL',
                'pgsql'                       => 'pgSQL',
                'select-timezone'             => 'Seleccionar Fuso Horário',
                'warning-message'             => 'Atenção! As configurações de idioma e moeda padrão não podem ser alteradas após definidas.',
                'united-states-dollar'        => 'Dólar Americano (USD)',
            ],

            'installation-processing' => [
                'krayin'       => 'Instalação do IAMI CRM',
                'krayin-info'  => 'A criar as tabelas da base de dados, aguarde um momento...',
                'title'        => 'Instalação',
            ],

            'installation-completed' => [
                'admin-panel'                => 'Painel de Administração',
                'krayin-forums'              => 'Fórum IAMI CRM',
                'customer-panel'             => 'Painel do Escritório',
                'explore-krayin-extensions'  => 'Explorar Extensões',
                'title'                      => 'Instalação Concluída',
                'title-info'                 => 'O IAMI CRM foi instalado com sucesso no seu sistema.',
            ],

            'ready-for-installation' => [
                'create-databsae-table'   => 'Criar tabelas da base de dados',
                'install'                 => 'Instalação',
                'start-installation'      => 'Iniciar Instalação',
                'title'                   => 'Pronto para Instalação',
            ],

            'start' => [
                'locale'        => 'Idioma',
                'main'          => 'Início',
                'select-locale' => 'Seleccionar Idioma',
                'title'         => 'Instalação do IAMI CRM',
                'welcome-title' => 'Bem-vindo ao IAMI CRM',
            ],

            'server-requirements' => [
                'php-version' => '8.1 ou superior',
                'title'       => 'Requisitos do Sistema',
            ],

            'back'                     => 'Voltar',
            'krayin'                   => 'IAMI CRM',
            'krayin-info'              => 'um projecto de gestão jurídica',
            'krayin-logo'              => 'Logótipo IAMI CRM',
            'continue'                 => 'Continuar',
            'installation-description' => 'A instalação do IAMI CRM envolve várias etapas. Segue-se uma descrição geral do processo de instalação.',
            'installation-info'        => 'Bem-vindo ao CRM Jurídico para Angola!',
            'installation-title'       => 'Bem-vindo à Instalação',
            'installation-wizard'      => 'Assistente de Instalação — Idioma',
            'title'                    => 'Instalador do IAMI CRM',
            'webkul'                   => 'Webkul',
        ],
    ],
];
