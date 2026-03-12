<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\Installer\Database\Seeders\DatabaseSeeder as KrayinDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(KrayinDatabaseSeeder::class);

        // Fase 4 — Módulos Jurídicos
        $this->call(LegalPipelineSeeder::class);
        $this->call(AngolanCourtsSeeder::class);
        $this->call(LegalAreasSeeder::class);
        $this->call(AngolanHolidaysSeeder::class);
        $this->call(LegalEmailTemplatesSeeder::class);

        // Fase 6 — Conformidade Legal Angolana
        $this->call(LegalRolesSeeder::class);
        $this->call(LegalComplianceSeeder::class);
    }
}
