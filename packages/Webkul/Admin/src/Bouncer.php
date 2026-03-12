<?php

namespace Webkul\Admin;

use Webkul\User\Repositories\UserRepository;

/**
 * Bouncer — Controlo de Acesso
 *
 * Fase 6 — Conformidade Legal Angolana
 *
 * Implementa controlo de acesso por processo em conformidade com:
 * - Lei n.º 22/11 (Protecção de Dados Pessoais) — confidencialidade de dados
 * - Estatuto da OAA — sigilo profissional e deontologia
 *
 * Perfis de acesso jurídico:
 * - Administrador    → view_permission = global  (acesso total)
 * - Supervisor       → view_permission = group   (acesso ao departamento)
 * - Advogado         → view_permission = individual (apenas processos atribuídos)
 * - Estagiário       → view_permission = individual (só leitura, processos atribuídos)
 */
class Bouncer
{
    /**
     * Checks if user allowed or not for certain action
     *
     * @param  string  $permission
     * @return void
     */
    public function hasPermission($permission)
    {
        if (auth()->guard('user')->check() && auth()->guard('user')->user()->role->permission_type == 'all') {
            return true;
        } else {
            if (! auth()->guard('user')->check() || ! auth()->guard('user')->user()->hasPermission($permission)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Checks if user allowed or not for certain action
     *
     * @param  string  $permission
     * @return void
     */
    public static function allow($permission)
    {
        if (! auth()->guard('user')->check() || ! auth()->guard('user')->user()->hasPermission($permission)) {
            abort(401, 'This action is unauthorized');
        }
    }

    /**
     * This function will return user ids of current user's groups
     *
     * @return array|null
     */
    public function getAuthorizedUserIds()
    {
        $user = auth()->guard('user')->user();

        if ($user->view_permission == 'global') {
            return null;
        }

        if ($user->view_permission == 'group') {
            return app(UserRepository::class)->getCurrentUserGroupsUserIds();
        } else {
            return [$user->id];
        }
    }

    /**
     * Verifica se o utilizador actual pode visualizar um processo específico.
     *
     * Regra de confidencialidade (Fase 6 — Lei n.º 22/11 / Estatuto OAA):
     * - Administrador (global): acesso a todos os processos
     * - Supervisor (group): acesso a todos os processos do seu departamento
     * - Advogado/Estagiário (individual): apenas processos que lhe foram atribuídos
     *
     * @param  mixed  $lead  Processo (Lead) a verificar — aceita model ou ID
     * @return bool
     */
    public function canViewLead($lead): bool
    {
        if (! auth()->guard('user')->check()) {
            return false;
        }

        $user = auth()->guard('user')->user();

        // Administradores têm acesso total
        if ($user->view_permission == 'global') {
            return true;
        }

        // Resolver o lead_user_id
        $leadUserId = is_object($lead) ? ($lead->user_id ?? null) : null;

        if ($leadUserId === null && is_numeric($lead)) {
            $leadModel = app(\Webkul\Lead\Repositories\LeadRepository::class)->find($lead);
            $leadUserId = $leadModel?->user_id;
        }

        // Supervisores podem ver processos do seu departamento
        if ($user->view_permission == 'group') {
            $groupUserIds = app(UserRepository::class)->getCurrentUserGroupsUserIds();

            return in_array($leadUserId, $groupUserIds ?? []);
        }

        // Advogados/Estagiários só vêem os seus processos
        return $leadUserId === $user->id;
    }

    /**
     * Asserts that the current user can view the given lead, aborting with 403 if not.
     *
     * @param  mixed  $lead
     * @return void
     */
    public function authorizeLeadAccess($lead): void
    {
        if (! $this->canViewLead($lead)) {
            abort(403, trans('admin::app.leads.view.unauthorized'));
        }
    }

    /**
     * Verifica se o utilizador actual é um Estagiário (acesso apenas de leitura).
     *
     * Estagiários têm o perfil com permission_type == 'custom' e
     * marcados com o papel 'estagiario' no sistema.
     *
     * @return bool
     */
    public function isIntern(): bool
    {
        if (! auth()->guard('user')->check()) {
            return false;
        }

        $user = auth()->guard('user')->user();

        return $user->role && str_contains(strtolower($user->role->name ?? ''), 'estagiário');
    }

    /**
     * Verifica se o utilizador pode editar/modificar um recurso.
     * Estagiários têm acesso somente leitura.
     *
     * @param  string  $permission
     * @return bool
     */
    public function canModify(string $permission): bool
    {
        if ($this->isIntern()) {
            return false;
        }

        return $this->hasPermission($permission);
    }
}
