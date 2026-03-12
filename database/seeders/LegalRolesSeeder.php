<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * LegalRolesSeeder — Fase 6: Conformidade Legal Angolana
 *
 * Cria os perfis de acesso padrão para escritórios de advocacia angolanos.
 *
 * Perfis em conformidade com:
 * - Estatuto da Ordem dos Advogados de Angola (OAA)
 * - Lei n.º 22/11 (Protecção de Dados Pessoais) — princípio do acesso mínimo necessário
 *
 * Hierarquia de acesso:
 * - Administrador     → Acesso total (global)
 * - Supervisor Jurídico → Acesso ao departamento (group)
 * - Advogado          → Apenas processos atribuídos (individual)
 * - Estagiário        → Somente leitura, processos atribuídos (individual)
 */
class LegalRolesSeeder extends Seeder
{
    /**
     * Permissões completas do sistema para o Administrador.
     */
    protected array $adminPermissions = [
        'dashboard',
        'leads',
        'leads.create',
        'leads.view',
        'leads.edit',
        'leads.delete',
        'hearings',
        'hearings.create',
        'hearings.view',
        'hearings.edit',
        'hearings.delete',
        'documents',
        'documents.create',
        'documents.view',
        'documents.edit',
        'documents.delete',
        'time-entries',
        'time-entries.create',
        'time-entries.edit',
        'time-entries.delete',
        'deadlines',
        'deadlines.create',
        'deadlines.view',
        'deadlines.edit',
        'deadlines.delete',
        'quotes',
        'quotes.create',
        'quotes.edit',
        'quotes.print',
        'quotes.delete',
        'mail',
        'mail.inbox',
        'mail.compose',
        'mail.view',
        'mail.edit',
        'mail.delete',
        'activities',
        'activities.create',
        'activities.edit',
        'activities.delete',
        'contacts',
        'contacts.persons',
        'contacts.persons.create',
        'contacts.persons.edit',
        'contacts.persons.delete',
        'contacts.persons.view',
        'contacts.organizations',
        'contacts.organizations.create',
        'contacts.organizations.edit',
        'contacts.organizations.delete',
        'products',
        'products.create',
        'products.edit',
        'products.delete',
        'products.view',
        'settings',
        'settings.user',
        'settings.user.groups',
        'settings.user.groups.create',
        'settings.user.groups.edit',
        'settings.user.groups.delete',
        'settings.user.roles',
        'settings.user.roles.create',
        'settings.user.roles.edit',
        'settings.user.roles.delete',
        'settings.user.users',
        'settings.user.users.create',
        'settings.user.users.edit',
        'settings.user.users.delete',
        'settings.lead',
        'settings.lead.pipelines',
        'settings.lead.pipelines.create',
        'settings.lead.pipelines.edit',
        'settings.lead.pipelines.delete',
        'settings.lead.sources',
        'settings.lead.sources.create',
        'settings.lead.sources.edit',
        'settings.lead.sources.delete',
        'settings.lead.types',
        'settings.lead.types.create',
        'settings.lead.types.edit',
        'settings.lead.types.delete',
        'settings.automation',
        'settings.automation.attributes',
        'settings.automation.attributes.create',
        'settings.automation.attributes.edit',
        'settings.automation.attributes.delete',
        'settings.automation.email_templates',
        'settings.automation.email_templates.create',
        'settings.automation.email_templates.edit',
        'settings.automation.email_templates.delete',
        'settings.automation.workflows',
        'settings.automation.workflows.create',
        'settings.automation.workflows.edit',
        'settings.automation.workflows.delete',
        'settings.other_settings',
        'settings.other_settings.tags',
        'settings.other_settings.tags.create',
        'settings.other_settings.tags.edit',
        'settings.other_settings.tags.delete',
        'configuration',
    ];

    /**
     * Permissões para Supervisor Jurídico (gestão do departamento).
     */
    protected array $supervisorPermissions = [
        'dashboard',
        'leads',
        'leads.create',
        'leads.view',
        'leads.edit',
        'leads.delete',
        'hearings',
        'hearings.create',
        'hearings.view',
        'hearings.edit',
        'hearings.delete',
        'documents',
        'documents.create',
        'documents.view',
        'documents.edit',
        'documents.delete',
        'time-entries',
        'time-entries.create',
        'time-entries.edit',
        'time-entries.delete',
        'deadlines',
        'deadlines.create',
        'deadlines.view',
        'deadlines.edit',
        'deadlines.delete',
        'quotes',
        'quotes.create',
        'quotes.edit',
        'quotes.print',
        'quotes.delete',
        'mail',
        'mail.inbox',
        'mail.compose',
        'mail.view',
        'mail.edit',
        'activities',
        'activities.create',
        'activities.edit',
        'activities.delete',
        'contacts',
        'contacts.persons',
        'contacts.persons.create',
        'contacts.persons.edit',
        'contacts.persons.view',
        'contacts.organizations',
        'contacts.organizations.create',
        'contacts.organizations.edit',
        'products',
        'products.view',
        'settings.other_settings',
        'settings.other_settings.tags',
        'settings.other_settings.tags.create',
    ];

    /**
     * Permissões para Advogado (gestão dos seus processos).
     */
    protected array $lawyerPermissions = [
        'dashboard',
        'leads',
        'leads.create',
        'leads.view',
        'leads.edit',
        'hearings',
        'hearings.create',
        'hearings.view',
        'hearings.edit',
        'hearings.delete',
        'documents',
        'documents.create',
        'documents.view',
        'documents.edit',
        'documents.delete',
        'time-entries',
        'time-entries.create',
        'time-entries.edit',
        'deadlines',
        'deadlines.create',
        'deadlines.view',
        'deadlines.edit',
        'quotes',
        'quotes.create',
        'quotes.edit',
        'quotes.print',
        'mail',
        'mail.inbox',
        'mail.compose',
        'mail.view',
        'activities',
        'activities.create',
        'activities.edit',
        'contacts',
        'contacts.persons',
        'contacts.persons.create',
        'contacts.persons.edit',
        'contacts.persons.view',
        'contacts.organizations',
        'contacts.organizations.view',
        'products',
        'products.view',
    ];

    /**
     * Permissões para Estagiário (somente leitura dos processos atribuídos).
     * Em conformidade com o Estatuto da OAA — supervisão obrigatória de estagiários.
     */
    protected array $internPermissions = [
        'dashboard',
        'leads',
        'leads.view',
        'hearings',
        'hearings.view',
        'documents',
        'documents.view',
        'time-entries',
        'time-entries.create',
        'deadlines',
        'deadlines.view',
        'quotes',
        'quotes.print',
        'mail',
        'mail.inbox',
        'mail.view',
        'activities',
        'contacts',
        'contacts.persons',
        'contacts.persons.view',
        'contacts.organizations',
        'products',
        'products.view',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name'            => 'Administrador',
                'description'     => 'Acesso total ao sistema. Gere utilizadores, configurações e todos os processos.',
                'permission_type' => 'all',
                'permissions'     => [],
                'view_permission' => 'global',
            ],
            [
                'name'            => 'Supervisor Jurídico',
                'description'     => 'Supervisiona os advogados do departamento. Acesso a todos os processos do grupo.',
                'permission_type' => 'custom',
                'permissions'     => $this->supervisorPermissions,
                'view_permission' => 'group',
            ],
            [
                'name'            => 'Advogado',
                'description'     => 'Gere os seus processos e clientes. Acesso restrito aos processos atribuídos.',
                'permission_type' => 'custom',
                'permissions'     => $this->lawyerPermissions,
                'view_permission' => 'individual',
            ],
            [
                'name'            => 'Estagiário',
                'description'     => 'Acesso somente leitura aos processos atribuídos. Supervisionado por Advogado ou Supervisor.',
                'permission_type' => 'custom',
                'permissions'     => $this->internPermissions,
                'view_permission' => 'individual',
            ],
        ];

        foreach ($roles as $roleData) {
            $viewPermission = $roleData['view_permission'];
            unset($roleData['view_permission']);

            $existing = DB::table('roles')->where('name', $roleData['name'])->first();

            if ($existing) {
                DB::table('roles')->where('id', $existing->id)->update([
                    'description'     => $roleData['description'],
                    'permission_type' => $roleData['permission_type'],
                    'permissions'     => json_encode($roleData['permissions']),
                ]);

                $this->command->info("Perfil actualizado: {$roleData['name']}");
            } else {
                DB::table('roles')->insert([
                    'name'            => $roleData['name'],
                    'description'     => $roleData['description'],
                    'permission_type' => $roleData['permission_type'],
                    'permissions'     => json_encode($roleData['permissions']),
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);

                $this->command->info("Perfil criado: {$roleData['name']} (view_permission: {$viewPermission})");
            }
        }

        $this->command->info('');
        $this->command->info('Perfis jurídicos configurados:');
        $this->command->info('  Administrador     → Acesso global (todos os processos)');
        $this->command->info('  Supervisor Jurídico → Acesso ao grupo (departamento)');
        $this->command->info('  Advogado          → Acesso individual (processos atribuídos)');
        $this->command->info('  Estagiário        → Somente leitura (processos atribuídos)');
        $this->command->info('');
        $this->command->info('Nota: Ao criar utilizadores, defina o campo view_permission:');
        $this->command->info('  global / group / individual');
    }
}
