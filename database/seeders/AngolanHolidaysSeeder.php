<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Documentação dos Feriados Angolanos.
 *
 * Este seeder serve como referência para o cálculo de prazos
 * em dias úteis no LegalDeadlineRepository.
 * Os feriados são calculados dinamicamente por ano.
 *
 * Feriados de Angola (Lei n.º 19/12, de 5 de Junho):
 * - 01/01 — Dia de Ano Novo
 * - 04/01 — Dia dos Mártires da Repressão Colonial
 * - 04/02 — Início da Luta Armada
 * - Variável — Carnaval (terça-feira)
 * - 08/03 — Dia Internacional da Mulher
 * - 04/04 — Dia da Paz e Reconciliação Nacional
 * - Variável — Sexta-feira Santa
 * - 01/05 — Dia do Trabalhador
 * - 17/09 — Dia do Herói Nacional (Aniversário de Jonas Savimbi)
 * - 02/11 — Dia dos Finados
 * - 11/11 — Dia da Independência Nacional
 * - 25/12 — Dia de Natal
 */
class AngolanHolidaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Os feriados angolanos são calculados dinamicamente.');
        $this->command->info('Consulte LegalDeadlineRepository::getAngolanHolidays()');
    }
}
