# Plano de Implementação — CRM Jurídico para Angola

## Visão Geral

Transformação do Krayin CRM (sistema genérico de gestão de relacionamento com clientes) num **CRM Jurídico** especializado para escritórios de advocacia e departamentos jurídicos em **Angola**. O frontend será inteiramente em **Português de Angola**, com a moeda padrão **Kwanza Angolano (AOA/Kz)**.

---

## Índice

1. [Análise do Estado Actual](#1-análise-do-estado-actual)
2. [Fase 1 — Localização e Internacionalização (i18n)](#fase-1--localização-e-internacionalização-i18n)
3. [Fase 2 — Adaptação da Moeda e Formato Numérico](#fase-2--adaptação-da-moeda-e-formato-numérico)
4. [Fase 3 — Reestruturação do Modelo de Dados (Domínio Jurídico)](#fase-3--reestruturação-do-modelo-de-dados-domínio-jurídico)
5. [Fase 4 — Novos Módulos Jurídicos](#fase-4--novos-módulos-jurídicos)
6. [Fase 5 — Adaptação do Frontend e UX](#fase-5--adaptação-do-frontend-e-ux)
7. [Fase 6 — Conformidade Legal Angolana](#fase-6--conformidade-legal-angolana)
8. [Fase 7 — Testes e Garantia de Qualidade](#fase-7--testes-e-garantia-de-qualidade)
9. [Fase 8 — Implantação e Formação](#fase-8--implantação-e-formação)
10. [Cronograma Estimado](#cronograma-estimado)
11. [Mapeamento de Entidades (CRM Genérico → Jurídico)](#mapeamento-de-entidades)

---

## 1. Análise do Estado Actual ✅ CONCLUÍDO

> **Estado:** Concluído em Março de 2026. Estrutura analisada, pacotes identificados, mapeamento de entidades documentado.

### Estrutura do CRM Actual (Krayin CRM)

O sistema é construído em **Laravel** com frontend em **Blade + Vue.js**, organizado em pacotes modulares:

| Pacote (Módulo)     | Função Actual                     | Adaptação Jurídica                         |
|---------------------|-----------------------------------|--------------------------------------------|
| `Admin`             | Painel administrativo             | Rebranding + tradução completa             |
| `Lead`              | Gestão de leads/oportunidades     | → **Processos / Casos Jurídicos**          |
| `Contact`           | Contactos (pessoas/organizações)  | → **Clientes / Partes Processuais**        |
| `Quote`             | Orçamentos/propostas              | → **Honorários / Propostas de Serviço**    |
| `Product`           | Produtos                          | → **Serviços Jurídicos**                   |
| `Activity`          | Actividades/calendário            | → **Diligências / Prazos Processuais**     |
| `Email`             | Gestão de e-mail                  | Mantém-se (com tradução)                   |
| `EmailTemplate`     | Templates de e-mail               | → Templates jurídicos angolanos            |
| `Tag`               | Etiquetas                         | → Etiquetas jurídicas                      |
| `Warehouse`         | Armazém/inventário                | → **Arquivo de Documentos**                |
| `Automation`        | Workflows e webhooks              | → Automações jurídicas                     |
| `Marketing`         | Campanhas e eventos               | Pode ser desactivado ou adaptado           |
| `DataGrid`          | Tabelas de dados                  | Tradução de colunas e filtros              |
| `DataTransfer`      | Importação/exportação             | Mantém-se (com tradução)                   |
| `Attribute`         | Atributos personalizados          | Novos atributos jurídicos                  |
| `Core`              | Configurações base                | Locale `pt_AO`, moeda AOA                  |
| `User`              | Gestão de utilizadores            | → Advogados/Estagiários/Staff              |
| `WebForm`           | Formulários web                   | → Formulário de pedido de consulta         |
| `Installer`         | Instalador                        | Configuração padrão Angola                 |

### Idiomas Disponíveis Actualmente
- `en` (Inglês)
- `pt_BR` (Português do Brasil)
- `es` (Espanhol)
- `ar` (Árabe)
- `fa` (Persa)
- `tr` (Turco)
- `vi` (Vietnamita)

**Nota:** Existe tradução `pt_BR` que servirá de base para `pt_AO`.

---

## Fase 1 — Localização e Internacionalização (i18n) ✅ CONCLUÍDO

> **Estado:** Concluído em Março de 2026.
> - Locale `pt_AO` criado em todos os pacotes (Admin, Installer, DataTransfer)
> - Ficheiros Laravel core traduzidos (`lang/pt_AO/`: auth, pagination, passwords, validation)
> - Terminologia jurídica angolana aplicada (Processos, Diligências, Advogados, etc.)
> - `pt_AO` definido como locale padrão em `config/app.php` e `core_config.php`
> - Moeda padrão definida como `AOA` (Kwanza Angolano)

### 1.1 Criar Locale `pt_AO` (Português de Angola)

**Ficheiros a criar/copiar e adaptar:**

```
packages/Webkul/Admin/src/Resources/lang/pt_AO/app.php
packages/Webkul/Installer/src/Resources/lang/pt_AO/app.php
packages/Webkul/DataTransfer/src/Resources/lang/pt_AO/app.php
lang/pt_AO/                (traduções gerais do Laravel)
```

**Passos:**

1. **Copiar** os ficheiros de `pt_BR` como base
   ```bash
   cp -r packages/Webkul/Admin/src/Resources/lang/pt_BR packages/Webkul/Admin/src/Resources/lang/pt_AO
   cp -r packages/Webkul/Installer/src/Resources/lang/pt_BR packages/Webkul/Installer/src/Resources/lang/pt_AO
   cp -r packages/Webkul/DataTransfer/src/Resources/lang/pt_BR packages/Webkul/DataTransfer/src/Resources/lang/pt_AO
   ```

2. **Adaptar** as traduções ao contexto angolano:
   - Substituir termos brasileiros por termos angolanos (ex.: "Celular" → "Telemóvel", "Endereço" → "Morada")
   - Adaptar terminologia jurídica ao direito angolano
   - Corrigir ortografia para a variante angolana do português

3. **Registar** o locale `pt_AO` no sistema:
   - Ficheiro: `packages/Webkul/Core/src/Core.php` — adicionar `pt_AO` à lista de locales
   - Ficheiro: `packages/Webkul/Admin/src/Config/core_config.php` — alterar o locale padrão para `pt_AO`

### 1.2 Terminologia Jurídica — Dicionário de Termos

| Termo Original (EN)  | Tradução Jurídica (pt_AO)             |
|----------------------|----------------------------------------|
| Lead                 | Processo / Caso                        |
| Deal                 | Acordo / Contrato                      |
| Contact              | Cliente / Parte Processual             |
| Person               | Pessoa Singular / Parte                |
| Organization         | Pessoa Colectiva / Entidade            |
| Quote                | Proposta de Honorários                 |
| Product              | Serviço Jurídico                       |
| Pipeline             | Fluxo Processual                       |
| Stage                | Fase Processual                        |
| Activity             | Diligência / Prazo                     |
| Source               | Origem do Caso                         |
| Tag                  | Etiqueta                               |
| Dashboard            | Painel de Controlo                     |
| Warehouse            | Arquivo de Documentos                  |
| Location             | Secção do Arquivo                      |
| Campaign             | Campanha                               |
| Workflow             | Fluxo de Trabalho                      |
| Webhook              | Integração Externa                     |
| Mail                 | Correspondência                        |
| Settings             | Definições                             |
| Configuration        | Configuração                           |
| Users                | Utilizadores                           |
| Groups               | Departamentos                          |
| Roles                | Perfis de Acesso                       |
| Attributes           | Campos Personalizados                  |

### 1.3 Traduzir Ficheiros de Validação do Laravel

```bash
cp -r lang/en lang/pt_AO
```

Traduzir os seguintes ficheiros:
- `lang/pt_AO/auth.php`
- `lang/pt_AO/pagination.php`
- `lang/pt_AO/passwords.php`
- `lang/pt_AO/validation.php`

---

## Fase 2 — Adaptação da Moeda e Formato Numérico ✅ CONCLUÍDO

> **Estado:** Concluído em Março de 2026.
> - Método `formatAOAPrice` adicionado em `Core.php` com formato `1.234.567,00 Kz`
> - Símbolo `Kz` devolvido por `currencySymbol('AOA')`
> - `formatBasePrice` usa automaticamente a formatação angolana quando `currency = AOA`
> - 21 províncias de Angola inseridas em `states.json` (ids 569–589, country_id=7)
> - Campo de moeda (AOA padrão) e fuso horário (Africa/Luanda) adicionados em `core_config.php`

### 2.1 Configurar Kwanza Angolano (AOA) como Moeda Padrão

**Ficheiros a modificar:**

1. **`packages/Webkul/Core/src/Core.php`**
   - Definir moeda padrão: `AOA`
   - Símbolo: `Kz`
   - Formato: `1.234.567,00 Kz`
   - Separador decimal: `,` (vírgula)
   - Separador de milhares: `.` (ponto)

2. **`config/app.php`**
   - Alterar `locale` para `pt_AO`
   - Alterar `faker_locale` para `pt_AO`

3. **Seeders do Instalador** (`packages/Webkul/Installer/`)
   - Garantir que os dados iniciais incluem Angola como país padrão
   - Incluir províncias angolanas como estados/regiões

### 2.2 Formatação de Valores nos Formulários

**Ficheiros a modificar:**

- Componentes Blade que formatam moeda em:
  - `packages/Webkul/Admin/src/Resources/views/quotes/` — propostas de honorários
  - `packages/Webkul/Admin/src/Resources/views/leads/` — valores dos processos
  - `packages/Webkul/Admin/src/Resources/views/products/` — preços dos serviços
  - `packages/Webkul/Admin/src/Resources/views/dashboard/` — gráficos e totais

### 2.3 Províncias de Angola

Inserir na tabela `country_states` as 21 províncias:

| Código | Província          |
|--------|--------------------|
| AO-BGO | Bengo             |
| AO-BGU | Benguela          |
| AO-BIE | Bié               |
| AO-CAB | Cabinda           |
| AO-CBG | Cubango           |
| AO-CUA | Cuando            |
| AO-CNO | Cuanza‑Norte      |
| AO-CUS | Cuanza‑Sul        |
| AO-CNN | Cunene            |
| AO-HUA | Huambo            |
| AO-HUI | Huíla             |
| AO-ICB | Icolo e Bengo     |
| AO-LUA | Luanda            |
| AO-LNO | Lunda‑Norte       |
| AO-LSU | Lunda‑Sul         |
| AO-MAL | Malanje           |
| AO-MOX | Moxico            |
| AO-MLE | Moxico Leste      |
| AO-NAM | Namibe            |
| AO-UIG | Uíge              |
| AO-ZAI | Zaire             |

---

## Fase 3 — Reestruturação do Modelo de Dados (Domínio Jurídico) ✅ CONCLUÍDO

> **Estado:** Concluído em Março de 2026.
> - Migration `add_legal_fields_to_leads_table` — 14 novos campos jurídicos na tabela `leads`
> - Migration `add_legal_fields_to_persons_table` — 11 novos campos na tabela `persons`
> - Migration `add_legal_fields_to_organizations_table` — 8 novos campos na tabela `organizations`
> - Migration `add_legal_fields_to_quotes_table` — 6 novos campos na tabela `quotes` (incl. IVA 14%)
> - Nova tabela `legal_documents` com relações a `leads`, `persons` e `users`
> - Nova tabela `hearings` (Audiências) com relações a `leads` e `users`
> - Nova tabela `time_entries` (Registo de Horas) com relações a `leads` e `users`
> - Nova tabela `legal_deadlines` (Prazos Processuais) com relações a `leads` e `users`
> - Models criados: `LegalDocument`, `Hearing`, `TimeEntry`, `LegalDeadline` (com Contracts e Proxies)
> - `Lead.php` actualizado com relações `legalDocuments()`, `hearings()`, `timeEntries()`, `legalDeadlines()`
> - `ModuleServiceProvider` actualizado com os 4 novos modelos

### 3.1 Novas Migrations

Criar as seguintes migrations para adicionar campos jurídicos:

#### 3.1.1 Tabela `leads` → Processos Jurídicos

```php
// Migration: add_legal_fields_to_leads_table.php
Schema::table('leads', function (Blueprint $table) {
    $table->string('case_number')->nullable()->unique();       // Número do Processo
    $table->string('court')->nullable();                        // Tribunal
    $table->string('court_section')->nullable();                // Secção/Vara
    $table->string('case_type')->nullable();                    // Tipo (Cível, Penal, Laboral, etc.)
    $table->string('jurisdiction')->nullable();                 // Jurisdição/Comarca
    $table->string('judge_name')->nullable();                   // Nome do Juiz
    $table->string('opponent_name')->nullable();                // Parte Contrária
    $table->string('opponent_lawyer')->nullable();              // Advogado da Parte Contrária
    $table->date('filing_date')->nullable();                    // Data de Entrada
    $table->date('next_hearing_date')->nullable();              // Próxima Audiência
    $table->string('urgency_level')->nullable();                // Nível de Urgência
    $table->string('legal_area')->nullable();                   // Área Jurídica
    $table->text('case_summary')->nullable();                   // Resumo do Caso
    $table->string('province')->nullable();                     // Província
});
```

#### 3.1.2 Tabela `persons` → Clientes/Partes

```php
// Migration: add_legal_fields_to_persons_table.php
Schema::table('persons', function (Blueprint $table) {
    $table->string('bi_number')->nullable();                    // Nº do BI (Bilhete de Identidade)
    $table->string('nif')->nullable();                          // NIF (Número de Identificação Fiscal)
    $table->string('passport_number')->nullable();              // Nº do Passaporte
    $table->string('nationality')->nullable()->default('Angolana'); // Nacionalidade
    $table->string('province')->nullable();                     // Província
    $table->string('municipality')->nullable();                 // Município
    $table->string('commune')->nullable();                      // Comuna
    $table->string('client_type')->nullable();                  // Tipo (Autor, Réu, Testemunha, etc.)
    $table->date('date_of_birth')->nullable();                  // Data de Nascimento
    $table->string('marital_status')->nullable();               // Estado Civil
    $table->string('profession')->nullable();                   // Profissão
});
```

#### 3.1.3 Tabela `organizations` → Pessoas Colectivas

```php
// Migration: add_legal_fields_to_organizations_table.php
Schema::table('organizations', function (Blueprint $table) {
    $table->string('nif')->nullable();                          // NIF da empresa
    $table->string('commercial_registry')->nullable();          // Registo Comercial
    $table->string('legal_form')->nullable();                   // Forma Jurídica (SU, Lda, SA, etc.)
    $table->string('province')->nullable();                     // Província
    $table->string('municipality')->nullable();                 // Município
    $table->string('sector')->nullable();                       // Sector de Actividade
    $table->string('representative_name')->nullable();          // Representante Legal
    $table->string('representative_role')->nullable();          // Cargo do Representante
});
```

#### 3.1.4 Tabela `quotes` → Propostas de Honorários

```php
// Migration: add_legal_fields_to_quotes_table.php
Schema::table('quotes', function (Blueprint $table) {
    $table->string('payment_terms')->nullable();                // Condições de Pagamento
    $table->string('billing_type')->nullable();                 // Tipo (Avença, Por Hora, Por Processo)
    $table->decimal('hourly_rate', 15, 2)->nullable();          // Taxa Horária (Kz)
    $table->decimal('retainer_fee', 15, 2)->nullable();         // Valor da Avença (Kz)
    $table->string('iva_regime')->nullable();                   // Regime de IVA
    $table->decimal('iva_percentage', 5, 2)->default(14.00);    // % IVA (14% em Angola)
});
```

#### 3.1.5 Nova Tabela: `legal_documents` — Documentos Jurídicos

```php
// Migration: create_legal_documents_table.php
Schema::create('legal_documents', function (Blueprint $table) {
    $table->id();
    $table->string('title');                                     // Título do Documento
    $table->string('document_type');                              // Tipo (Petição, Contestação, Recurso, etc.)
    $table->text('description')->nullable();                     // Descrição
    $table->string('file_path');                                 // Caminho do Ficheiro
    $table->string('file_type')->nullable();                     // Tipo de Ficheiro
    $table->unsignedBigInteger('lead_id')->nullable();           // Processo associado
    $table->unsignedBigInteger('person_id')->nullable();         // Cliente associado
    $table->unsignedBigInteger('user_id');                       // Advogado responsável
    $table->string('status')->default('rascunho');               // Estado
    $table->date('due_date')->nullable();                        // Data limite
    $table->date('filing_date')->nullable();                     // Data de protocolo
    $table->string('court_reference')->nullable();               // Referência no tribunal
    $table->timestamps();

    $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
    $table->foreign('person_id')->references('id')->on('persons')->onDelete('cascade');
    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
});
```

#### 3.1.6 Nova Tabela: `hearings` — Audiências

```php
// Migration: create_hearings_table.php
Schema::create('hearings', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('lead_id');                       // Processo
    $table->string('hearing_type');                               // Tipo (Julgamento, Instrução, Conciliação)
    $table->datetime('scheduled_at');                             // Data/Hora agendada
    $table->string('court');                                      // Tribunal
    $table->string('court_room')->nullable();                    // Sala
    $table->string('judge_name')->nullable();                    // Juiz
    $table->text('notes')->nullable();                           // Observações
    $table->string('status')->default('agendada');               // Estado
    $table->text('outcome')->nullable();                         // Resultado
    $table->unsignedBigInteger('user_id');                       // Advogado responsável
    $table->timestamps();

    $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
});
```

#### 3.1.7 Nova Tabela: `time_entries` — Registo de Horas

```php
// Migration: create_time_entries_table.php
Schema::create('time_entries', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('lead_id');                       // Processo
    $table->unsignedBigInteger('user_id');                       // Advogado
    $table->date('entry_date');                                   // Data
    $table->decimal('hours', 5, 2);                              // Horas trabalhadas
    $table->text('description');                                  // Descrição do trabalho
    $table->string('activity_type');                              // Tipo de actividade
    $table->decimal('hourly_rate', 15, 2)->nullable();           // Taxa horária
    $table->decimal('total_amount', 15, 2)->nullable();          // Valor total
    $table->boolean('billable')->default(true);                  // Facturável
    $table->boolean('billed')->default(false);                   // Facturado
    $table->timestamps();

    $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
});
```

#### 3.1.8 Nova Tabela: `legal_deadlines` — Prazos Processuais

```php
// Migration: create_legal_deadlines_table.php
Schema::create('legal_deadlines', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('lead_id');                       // Processo
    $table->string('title');                                      // Título do prazo
    $table->text('description')->nullable();                     // Descrição
    $table->date('start_date');                                   // Data início
    $table->date('due_date');                                     // Data limite
    $table->integer('business_days')->nullable();                // Dias úteis
    $table->string('status')->default('pendente');               // Estado
    $table->string('priority')->default('normal');               // Prioridade
    $table->boolean('court_deadline')->default(false);           // Prazo judicial
    $table->unsignedBigInteger('user_id');                       // Responsável
    $table->timestamps();

    $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
});
```

### 3.2 Novos Models

Criar os seguintes Models no padrão do Krayin (com Contracts e Proxies):

```
packages/Webkul/Lead/src/Models/LegalDocument.php
packages/Webkul/Lead/src/Models/Hearing.php
packages/Webkul/Lead/src/Models/TimeEntry.php
packages/Webkul/Lead/src/Models/LegalDeadline.php
```

### 3.3 Novos Atributos Personalizados

Adicionar via seeders os seguintes atributos ao sistema de atributos:

**Para Leads (Processos):**
- `case_number` — Número do Processo (texto, único)
- `court` — Tribunal (selecção)
- `legal_area` — Área Jurídica (selecção: Cível, Penal, Laboral, Comercial, Família, Administrativo, Fiscal)
- `case_type` — Tipo de Acção (selecção)
- `urgency_level` — Urgência (selecção: Baixa, Normal, Alta, Urgente)
- `province` — Província (selecção com as 21 províncias)

**Para Persons (Clientes):**
- `bi_number` — Nº do BI
- `nif` — NIF
- `nationality` — Nacionalidade
- `client_type` — Tipo de Cliente (selecção: Autor, Réu, Testemunha, Interveniente)

---

## Fase 4 — Novos Módulos Jurídicos

### 4.1 Módulo de Processos (adaptação do Lead)

**Ficheiros a modificar:**

| Ficheiro                                          | Alteração                                    |
|--------------------------------------------------|----------------------------------------------|
| `packages/Webkul/Lead/src/Models/Lead.php`       | Adicionar relações para documentos, audiências, prazos, horas |
| `packages/Webkul/Admin/src/Http/Controllers/Lead/` | Adaptar controllers para campos jurídicos    |
| `packages/Webkul/Admin/src/Resources/views/leads/` | Redesenhar views com campos jurídicos       |
| `packages/Webkul/Admin/src/DataGrids/Lead/`      | Adicionar colunas jurídicas nas tabelas      |
| `packages/Webkul/Lead/src/Repositories/`          | Adaptar repositórios                         |

**Pipeline padrão para processos jurídicos:**

| Ordem | Fase Processual             | Probabilidade |
|-------|-----------------------------|---------------|
| 1     | Consulta Inicial            | 10%           |
| 2     | Análise do Caso             | 20%           |
| 3     | Proposta de Honorários      | 30%           |
| 4     | Processo em Curso           | 50%           |
| 5     | Fase de Instrução           | 60%           |
| 6     | Audiência de Julgamento     | 80%           |
| 7     | Aguardar Sentença           | 90%           |
| 8     | Recurso                     | 70%           |
| 9     | Encerrado — Ganho           | 100%          |
| 10    | Encerrado — Perdido         | 0%            |

### 4.2 Módulo de Audiências

**Novos ficheiros a criar:**

```
packages/Webkul/Admin/src/Http/Controllers/Hearing/HearingController.php
packages/Webkul/Admin/src/Resources/views/hearings/index.blade.php
packages/Webkul/Admin/src/Resources/views/hearings/create.blade.php
packages/Webkul/Admin/src/Resources/views/hearings/view.blade.php
packages/Webkul/Admin/src/DataGrids/Hearing/HearingDataGrid.php
packages/Webkul/Lead/src/Repositories/HearingRepository.php
```

**Funcionalidades:**
- Calendário de audiências com vista mensal/semanal
- Alertas automáticos (24h e 1h antes da audiência)
- Integração com módulo de Actividades
- Registo de resultado da audiência
- Vinculação ao processo e advogado responsável

### 4.3 Módulo de Documentos Jurídicos

**Novos ficheiros a criar:**

```
packages/Webkul/Admin/src/Http/Controllers/Document/DocumentController.php
packages/Webkul/Admin/src/Resources/views/documents/index.blade.php
packages/Webkul/Admin/src/Resources/views/documents/create.blade.php
packages/Webkul/Admin/src/Resources/views/documents/view.blade.php
packages/Webkul/Admin/src/DataGrids/Document/DocumentDataGrid.php
packages/Webkul/Lead/src/Repositories/LegalDocumentRepository.php
```

**Tipos de documentos pré-configurados:**
- Petição Inicial
- Contestação
- Réplica / Tréplica
- Recurso
- Procuração
- Contrato
- Parecer Jurídico
- Requerimento
- Notificação
- Sentença / Acórdão

### 4.4 Módulo de Registo de Horas

**Novos ficheiros a criar:**

```
packages/Webkul/Admin/src/Http/Controllers/TimeEntry/TimeEntryController.php
packages/Webkul/Admin/src/Resources/views/time-entries/index.blade.php
packages/Webkul/Admin/src/Resources/views/time-entries/create.blade.php
packages/Webkul/Admin/src/DataGrids/TimeEntry/TimeEntryDataGrid.php
packages/Webkul/Lead/src/Repositories/TimeEntryRepository.php
```

### 4.5 Módulo de Prazos Processuais

**Novos ficheiros a criar:**

```
packages/Webkul/Admin/src/Http/Controllers/Deadline/DeadlineController.php
packages/Webkul/Admin/src/Resources/views/deadlines/index.blade.php
packages/Webkul/Admin/src/Resources/views/deadlines/calendar.blade.php
packages/Webkul/Admin/src/DataGrids/Deadline/DeadlineDataGrid.php
packages/Webkul/Lead/src/Repositories/LegalDeadlineRepository.php
```

**Funcionalidades:**
- Cálculo automático de prazos em dias úteis (considerando feriados angolanos)
- Alertas escalonados (5 dias, 2 dias, 1 dia, dia do vencimento)
- Vista em calendário
- Feriados angolanos pré-configurados

### 4.6 Feriados Angolanos (para cálculo de prazos)

| Data       | Feriado                                    |
|------------|-------------------------------------------|
| 01/01      | Dia de Ano Novo                           |
| 04/01      | Dia dos Mártires da Repressão Colonial    |
| 04/02      | Início da Luta Armada                     |
| Variável   | Carnaval                                   |
| 08/03      | Dia Internacional da Mulher               |
| 04/04      | Dia da Paz e Reconciliação Nacional       |
| Variável   | Sexta-feira Santa                          |
| 01/05      | Dia do Trabalhador                        |
| 17/09      | Dia do Herói Nacional                     |
| 02/11      | Dia dos Finados                           |
| 11/11      | Dia da Independência Nacional             |
| 25/12      | Dia de Natal                              |

---

## Fase 5 — Adaptação do Frontend e UX

### 5.1 Menu Principal (Sidebar)

**Ficheiro:** `packages/Webkul/Admin/src/Config/menu.php`

Nova estrutura do menu:

```
📊 Painel de Controlo
📋 Processos (antigo Leads)
    ├── Todos os Processos
    ├── Kanban Processual
    └── Processos Urgentes
⚖️ Audiências (novo)
📄 Documentos (novo)
⏱️ Horas (novo)
📅 Prazos (novo)
💰 Honorários (antigo Quotes)
📧 Correspondência (antigo Mail)
    ├── Caixa de Entrada
    ├── Rascunhos
    ├── Enviados
    └── Lixo
📋 Actividades / Diligências
👥 Clientes (antigo Contacts)
    ├── Pessoas Singulares
    └── Pessoas Colectivas
⚙️ Definições
    ├── Utilizadores
    │   ├── Departamentos
    │   ├── Perfis de Acesso
    │   └── Advogados/Staff
    ├── Processos
    │   ├── Fluxos Processuais
    │   ├── Origens
    │   └── Tipos
    ├── Arquivo de Documentos
    ├── Automações
    │   ├── Campos Personalizados
    │   ├── Modelos de E-mail
    │   ├── Fluxos de Trabalho
    │   └── Importação/Exportação
    └── Outras Definições
        └── Etiquetas
🔧 Configuração
```

### 5.2 Painel de Controlo (Dashboard)

**Ficheiro:** `packages/Webkul/Admin/src/Resources/views/dashboard/`

Novos widgets:

1. **Processos Activos** — Total de processos em curso
2. **Audiências da Semana** — Próximas audiências agendadas
3. **Prazos a Vencer** — Prazos nos próximos 5 dias úteis
4. **Honorários Pendentes** — Valor total em Kz de honorários por cobrar
5. **Horas Facturáveis** — Total de horas não facturadas
6. **Processos por Área Jurídica** — Gráfico circular
7. **Processos por Província** — Mapa ou gráfico de barras
8. **Performance por Advogado** — Processos ganhos vs. perdidos
9. **Receitas Mensais** — Gráfico em Kz

### 5.3 Formulários

Adaptar todos os formulários com:
- Máscaras de input para NIF e BI angolano
- Formato de telemóvel angolano: `+244 9XX XXX XXX`
- Campos de morada com Província → Município → Comuna
- Datas no formato `DD/MM/AAAA`
- Valores monetários no formato `1.234.567,00 Kz`

### 5.4 Templates de E-mail Jurídicos

Criar templates padrão em `packages/Webkul/EmailTemplate/`:

1. **Confirmação de Consulta** — E-mail ao cliente após marcação
2. **Proposta de Honorários** — Envio de proposta formal
3. **Lembrete de Audiência** — Notificação de audiência próxima
4. **Actualização do Processo** — Informar cliente sobre andamento
5. **Prazo a Vencer** — Alerta interno para advogados
6. **Recibo de Pagamento** — Confirmação de pagamento recebido

---

## Fase 6 — Conformidade Legal Angolana

### 6.1 Legislação Relevante

O sistema deve estar em conformidade com:

- **Lei da Protecção de Dados Pessoais** (Lei n.º 22/11, de 17 de Junho) — Protecção de dados dos clientes
- **Código de Processo Civil Angolano** — Prazos e fases processuais
- **Código de Processo Penal Angolano** — Fases do processo penal
- **Estatuto da Ordem dos Advogados de Angola (OAA)** — Regras deontológicas
- **Código do Imposto sobre o Valor Acrescentado (IVA)** — Taxa de 14%
- **Lei do Imposto Industrial** — Obrigações fiscais

### 6.2 Campos Obrigatórios por Lei

- NIF para facturação
- Registo de horas para justificação de honorários
- Confidencialidade de dados (controlo de acesso por processo)
- Prazos processuais conforme os códigos processuais

### 6.3 IVA — Imposto sobre o Valor Acrescentado

- Taxa padrão: **14%**
- Aplicar automaticamente nos cálculos de honorários
- Permitir isenção de IVA quando aplicável
- Gerar subtotais com e sem IVA

### 6.4 Controlo de Acesso por Processo

Adaptar o sistema de permissões (`packages/Webkul/Admin/src/Bouncer.php`) para:

- **Confidencialidade:** Cada advogado só vê os processos que lhe estão atribuídos
- **Supervisores:** Podem ver todos os processos do seu departamento
- **Administradores:** Acesso total
- **Estagiários:** Acesso somente leitura a processos atribuídos

---

## Fase 7 — Testes e Garantia de Qualidade

### 7.1 Testes Unitários

```
tests/Unit/Legal/
    ├── CaseNumberGenerationTest.php
    ├── DeadlineCalculationTest.php
    ├── HonorariumCalculationTest.php
    ├── IvaCalculationTest.php
    ├── BusinessDaysCalculationTest.php
    └── AngolanHolidaysTest.php
```

### 7.2 Testes de Funcionalidade (Feature)

```
tests/Feature/Legal/
    ├── ProcessManagementTest.php
    ├── HearingManagementTest.php
    ├── DocumentManagementTest.php
    ├── TimeEntryManagementTest.php
    ├── DeadlineManagementTest.php
    ├── HonorariumManagementTest.php
    └── AccessControlTest.php
```

### 7.3 Testes de Interface

- Verificar que todas as páginas estão em `pt_AO`
- Verificar formatação de moeda (Kz)
- Verificar formato de datas (DD/MM/AAAA)
- Verificar máscaras de input (NIF, BI, telemóvel)
- Testar responsividade em dispositivos móveis

### 7.4 Checklist de Qualidade

- [x] Todas as strings do frontend traduzidas para pt_AO *(Fase 1 concluída)*
- [ ] Nenhuma string hardcoded em inglês no frontend
- [x] Moeda exibida como Kz em todos os contextos *(formatAOAPrice implementado — Fase 2 concluída)*
- [ ] Datas no formato DD/MM/AAAA
- [ ] Números de telemóvel com formato angolano
- [x] Províncias angolanas disponíveis em todos os selectores *(21 províncias inseridas — Fase 2 concluída)*
- [ ] IVA de 14% calculado correctamente
- [ ] Prazos em dias úteis calculados com feriados angolanos
- [ ] Controlo de acesso por processo funcional
- [ ] Templates de e-mail em português de Angola

---

## Fase 8 — Implantação e Formação

### 8.1 Dados Iniciais (Seeders)

Criar seeders para:

1. **Tribunais de Angola:**
   - Tribunal Supremo
   - Tribunal Constitucional
   - Tribunal de Contas
   - Tribunais Provinciais (21 províncias)
   - Tribunais de Comarca
   - Tribunal do Trabalho
   - Tribunal Administrativo

2. **Áreas Jurídicas:**
   - Direito Civil
   - Direito Penal
   - Direito Laboral
   - Direito Comercial
   - Direito da Família
   - Direito Administrativo
   - Direito Fiscal e Aduaneiro
   - Direito Imobiliário
   - Direito de Propriedade Intelectual
   - Direito Ambiental
   - Direito Marítimo
   - Direito Petrolífero e Mineiro

3. **Tipos de Acção:**
   - Acção Declarativa
   - Acção Executiva
   - Providência Cautelar
   - Recurso
   - Inventário
   - Insolvência
   - Arbitragem

4. **Pipeline Processual** (conforme Fase 4.1)

5. **Utilizador padrão:**
   - E-mail: `admin@escritorio.ao`
   - Perfil: Administrador

### 8.2 Migração de Dados (se aplicável)

Se houver dados existentes no CRM genérico:

1. Exportar dados actuais via módulo DataTransfer
2. Mapear campos antigos para campos jurídicos
3. Importar dados adaptados
4. Validar integridade dos dados

### 8.3 Formação

Preparar materiais de formação:

1. **Manual do Utilizador** (pt_AO) — Guia completo de utilização
2. **Guia Rápido** — Operações mais frequentes
3. **Vídeos tutoriais** — Demonstração de funcionalidades

---

## Cronograma Estimado

| Fase | Descrição                                  | Duração Estimada | Estado     |
|------|--------------------------------------------|------------------|------------|
| —    | Análise do Estado Actual                  | —                | ✅ Concluído |
| 1    | Localização e i18n (pt_AO)                | 1-2 semanas      | ✅ Concluído |
| 2    | Moeda e formato numérico                  | 3-5 dias         | ✅ Concluído |
| 3    | Reestruturação do modelo de dados         | 1-2 semanas      | ✅ Concluído |
| 4    | Novos módulos jurídicos                    | 3-4 semanas      | ⏳ Pendente |
| 5    | Adaptação do frontend e UX               | 2-3 semanas      | ⏳ Pendente |
| 6    | Conformidade legal angolana                | 1 semana         | ⏳ Pendente |
| 7    | Testes e garantia de qualidade            | 1-2 semanas      | ⏳ Pendente |
| 8    | Implantação e formação                    | 1 semana         | ⏳ Pendente |
| **Total** |                                       | **10-15 semanas** | — |

---

## Mapeamento de Entidades

### CRM Genérico → CRM Jurídico Angola

```
┌─────────────────────────────────────────────────────────┐
│                  MAPEAMENTO DE ENTIDADES                │
├──────────────────────┬──────────────────────────────────┤
│   CRM Genérico       │   CRM Jurídico Angola            │
├──────────────────────┼──────────────────────────────────┤
│   Lead               │   Processo / Caso                │
│   Pipeline           │   Fluxo Processual               │
│   Stage              │   Fase Processual                │
│   Person             │   Pessoa Singular / Cliente       │
│   Organization       │   Pessoa Colectiva / Entidade     │
│   Quote              │   Proposta de Honorários          │
│   Quote Item         │   Serviço / Diligência            │
│   Product            │   Serviço Jurídico                │
│   Activity           │   Diligência / Prazo              │
│   Warehouse          │   Arquivo de Documentos           │
│   Location           │   Secção do Arquivo               │
│   Tag                │   Etiqueta                        │
│   User               │   Advogado / Colaborador          │
│   Group              │   Departamento                    │
│   Role               │   Perfil de Acesso                │
│   Email              │   Correspondência                 │
│   WebForm            │   Formulário de Consulta          │
│   — (novo)           │   Audiência                       │
│   — (novo)           │   Documento Jurídico              │
│   — (novo)           │   Registo de Horas                │
│   — (novo)           │   Prazo Processual                │
└──────────────────────┴──────────────────────────────────┘
```

---

## Estrutura de Ficheiros Novos/Modificados (Resumo)

```
packages/
├── Webkul/
│   ├── Admin/
│   │   └── src/
│   │       ├── Config/
│   │       │   ├── menu.php                    (MODIFICAR)
│   │       │   ├── acl.php                     (MODIFICAR)
│   │       │   └── core_config.php             (MODIFICAR)
│   │       ├── Http/Controllers/
│   │       │   ├── Hearing/                    (NOVO)
│   │       │   ├── Document/                   (NOVO)
│   │       │   ├── TimeEntry/                  (NOVO)
│   │       │   └── Deadline/                   (NOVO)
│   │       ├── DataGrids/
│   │       │   ├── Hearing/                    (NOVO)
│   │       │   ├── Document/                   (NOVO)
│   │       │   ├── TimeEntry/                  (NOVO)
│   │       │   └── Deadline/                   (NOVO)
│   │       └── Resources/
│   │           ├── lang/pt_AO/                 (NOVO)
│   │           └── views/
│   │               ├── hearings/               (NOVO)
│   │               ├── documents/              (NOVO)
│   │               ├── time-entries/           (NOVO)
│   │               ├── deadlines/              (NOVO)
│   │               ├── leads/                  (MODIFICAR)
│   │               ├── quotes/                 (MODIFICAR)
│   │               ├── contacts/               (MODIFICAR)
│   │               └── dashboard/              (MODIFICAR)
│   ├── Core/
│   │   └── src/Core.php                        (MODIFICAR)
│   ├── Installer/
│   │   └── src/Resources/lang/pt_AO/          (NOVO)
│   ├── DataTransfer/
│   │   └── src/Resources/lang/pt_AO/          (NOVO)
│   └── Lead/
│       └── src/
│           ├── Database/Migrations/            (NOVOS)
│           ├── Models/
│           │   ├── LegalDocument.php           (NOVO)
│           │   ├── Hearing.php                 (NOVO)
│           │   ├── TimeEntry.php               (NOVO)
│           │   └── LegalDeadline.php           (NOVO)
│           └── Repositories/
│               ├── HearingRepository.php       (NOVO)
│               ├── LegalDocumentRepository.php (NOVO)
│               ├── TimeEntryRepository.php     (NOVO)
│               └── LegalDeadlineRepository.php (NOVO)
├── routes/
│   └── admin.php                               (MODIFICAR)
├── lang/pt_AO/                                 (NOVO)
├── config/app.php                              (MODIFICAR)
└── database/
    └── seeders/
        ├── AngolanCourtsSeeder.php             (NOVO)
        ├── LegalAreasSeeder.php                (NOVO)
        ├── AngolanProvincesSeeder.php          (NOVO)
        ├── LegalPipelineSeeder.php             (NOVO)
        └── AngolanHolidaysSeeder.php           (NOVO)
```

---

## Notas Finais

- **Prioridade:** Fases 1-3 devem ser implementadas primeiro, pois são a base para tudo o resto.
- **Abordagem incremental:** Cada fase deve ser testada antes de avançar para a seguinte.
- **Backward compatibility:** Manter a estrutura base do Krayin CRM para facilitar futuras actualizações.
- **Segurança:** Dados de clientes são confidenciais — garantir encriptação e controlo de acesso rigoroso.
- **Backup:** Sempre fazer backup antes de executar migrations destrutivas.

---

*Documento criado em Março de 2026 para o projecto IAMI CRM — CRM Jurídico Angola*
