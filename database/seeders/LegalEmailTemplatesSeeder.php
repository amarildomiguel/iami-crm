<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LegalEmailTemplatesSeeder extends Seeder
{
    /**
     * Seed the legal email templates for the Angolan law firm CRM.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $templates = [
            [
                'name'       => 'Confirmação de Consulta',
                'subject'    => 'Confirmação da sua consulta jurídica — {%leads.title%}',
                'content'    => $this->consultationConfirmation(),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => 'Proposta de Honorários',
                'subject'    => 'Proposta de Honorários — {%quotes.subject%}',
                'content'    => $this->feeProposal(),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => 'Lembrete de Audiência',
                'subject'    => 'Lembrete: Audiência agendada — {%leads.title%}',
                'content'    => $this->hearingReminder(),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => 'Actualização do Processo',
                'subject'    => 'Actualização do seu processo — {%leads.title%}',
                'content'    => $this->processUpdate(),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => 'Prazo a Vencer (Alerta Interno)',
                'subject'    => '[PRAZO] {%leads.title%} — Prazo a vencer em breve',
                'content'    => $this->deadlineAlert(),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => 'Recibo de Pagamento',
                'subject'    => 'Confirmação de pagamento recebido — {%leads.title%}',
                'content'    => $this->paymentReceipt(),
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($templates as $template) {
            DB::table('email_templates')->updateOrInsert(
                ['name' => $template['name']],
                $template
            );
        }
    }

    private function consultationConfirmation(): string
    {
        return <<<HTML
<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; color: #333;">
    <div style="background: #1a365d; padding: 20px; text-align: center;">
        <h1 style="color: #ffffff; margin: 0; font-size: 20px;">Confirmação de Consulta Jurídica</h1>
    </div>

    <div style="padding: 30px 20px;">
        <p style="font-size: 16px;">Exmo(a). Sr(a). {%persons.name%},</p>

        <p style="font-size: 15px; line-height: 1.6;">
            Agradecemos o seu contacto. Temos o prazer de confirmar a marcação da sua consulta jurídica.
        </p>

        <table style="width: 100%; border-collapse: collapse; margin: 20px 0; background: #f7f9fc; border-radius: 8px;">
            <tr>
                <td style="padding: 10px 15px; font-weight: bold; color: #1a365d; width: 40%;">Processo:</td>
                <td style="padding: 10px 15px;">{%leads.title%}</td>
            </tr>
            <tr style="background: #edf2f7;">
                <td style="padding: 10px 15px; font-weight: bold; color: #1a365d;">Advogado Responsável:</td>
                <td style="padding: 10px 15px;">{%users.name%}</td>
            </tr>
            <tr>
                <td style="padding: 10px 15px; font-weight: bold; color: #1a365d;">Área Jurídica:</td>
                <td style="padding: 10px 15px;">{%leads.legal_area%}</td>
            </tr>
        </table>

        <p style="font-size: 15px; line-height: 1.6;">
            Caso necessite de reagendar ou tenha alguma questão, não hesite em contactar-nos.
        </p>

        <p style="font-size: 14px; color: #666; margin-top: 30px; padding-top: 15px; border-top: 1px solid #e2e8f0;">
            Este é um e-mail automático enviado pelo sistema IAMI CRM. Por favor, não responda directamente a este e-mail.
        </p>
    </div>

    <div style="background: #1a365d; padding: 15px; text-align: center;">
        <p style="color: #a0aec0; font-size: 12px; margin: 0;">
            © {%core.date%} — Sistema CRM Jurídico Angola
        </p>
    </div>
</div>
HTML;
    }

    private function feeProposal(): string
    {
        return <<<HTML
<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; color: #333;">
    <div style="background: #1a365d; padding: 20px; text-align: center;">
        <h1 style="color: #ffffff; margin: 0; font-size: 20px;">Proposta de Honorários</h1>
    </div>

    <div style="padding: 30px 20px;">
        <p style="font-size: 16px;">Exmo(a). Sr(a). {%persons.name%},</p>

        <p style="font-size: 15px; line-height: 1.6;">
            Após análise do seu caso, temos o prazer de apresentar a nossa proposta de honorários para a prestação dos serviços jurídicos solicitados.
        </p>

        <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
            <thead>
                <tr style="background: #1a365d; color: white;">
                    <th style="padding: 10px 15px; text-align: left;">Referência da Proposta</th>
                    <th style="padding: 10px 15px; text-align: right;">Valor</th>
                </tr>
            </thead>
            <tbody>
                <tr style="background: #f7f9fc;">
                    <td style="padding: 10px 15px;">{%quotes.subject%}</td>
                    <td style="padding: 10px 15px; text-align: right; font-weight: bold;">{%quotes.grand_total%} Kz</td>
                </tr>
                <tr>
                    <td style="padding: 10px 15px; color: #666;">IVA (14%)</td>
                    <td style="padding: 10px 15px; text-align: right; color: #666;">{%quotes.tax_amount%} Kz</td>
                </tr>
                <tr style="background: #1a365d; color: white;">
                    <td style="padding: 10px 15px; font-weight: bold;">Total c/ IVA</td>
                    <td style="padding: 10px 15px; text-align: right; font-weight: bold;">{%quotes.grand_total%} Kz</td>
                </tr>
            </tbody>
        </table>

        <div style="background: #fff8e1; border-left: 4px solid #f59e0b; padding: 15px; margin: 20px 0; border-radius: 4px;">
            <p style="margin: 0; font-size: 14px; color: #78350f;">
                <strong>Validade da proposta:</strong> {%quotes.expired_at%}
            </p>
        </div>

        <p style="font-size: 15px; line-height: 1.6;">
            Esta proposta está sujeita à aceitação dos termos e condições do nosso escritório. Para aceitar ou esclarecer dúvidas, por favor contacte-nos.
        </p>

        <p style="font-size: 14px; color: #666; margin-top: 30px; padding-top: 15px; border-top: 1px solid #e2e8f0;">
            Este é um e-mail automático enviado pelo sistema IAMI CRM.
        </p>
    </div>

    <div style="background: #1a365d; padding: 15px; text-align: center;">
        <p style="color: #a0aec0; font-size: 12px; margin: 0;">
            © {%core.date%} — Sistema CRM Jurídico Angola
        </p>
    </div>
</div>
HTML;
    }

    private function hearingReminder(): string
    {
        return <<<HTML
<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; color: #333;">
    <div style="background: #6366f1; padding: 20px; text-align: center;">
        <h1 style="color: #ffffff; margin: 0; font-size: 20px;">⚖️ Lembrete de Audiência</h1>
    </div>

    <div style="padding: 30px 20px;">
        <p style="font-size: 16px;">Exmo(a). Sr(a). {%persons.name%},</p>

        <div style="background: #fef3c7; border: 1px solid #f59e0b; border-radius: 8px; padding: 20px; margin: 20px 0;">
            <h2 style="color: #92400e; margin: 0 0 10px 0; font-size: 16px;">Audiência Agendada</h2>
            <table style="width: 100%;">
                <tr>
                    <td style="color: #78350f; font-weight: bold; padding: 5px 0; width: 40%;">Processo:</td>
                    <td style="padding: 5px 0;">{%leads.title%}</td>
                </tr>
                <tr>
                    <td style="color: #78350f; font-weight: bold; padding: 5px 0;">Número do Processo:</td>
                    <td style="padding: 5px 0;">{%leads.case_number%}</td>
                </tr>
                <tr>
                    <td style="color: #78350f; font-weight: bold; padding: 5px 0;">Tribunal:</td>
                    <td style="padding: 5px 0;">{%leads.court%}</td>
                </tr>
                <tr>
                    <td style="color: #78350f; font-weight: bold; padding: 5px 0;">Advogado:</td>
                    <td style="padding: 5px 0;">{%users.name%}</td>
                </tr>
            </table>
        </div>

        <p style="font-size: 15px; line-height: 1.6;">
            Solicitamos que compareça com antecedência suficiente. Em caso de impedimento, contacte imediatamente o seu advogado.
        </p>

        <p style="font-size: 14px; color: #666; margin-top: 30px; padding-top: 15px; border-top: 1px solid #e2e8f0;">
            Este é um e-mail automático enviado pelo sistema IAMI CRM.
        </p>
    </div>

    <div style="background: #6366f1; padding: 15px; text-align: center;">
        <p style="color: #e0e7ff; font-size: 12px; margin: 0;">
            © {%core.date%} — Sistema CRM Jurídico Angola
        </p>
    </div>
</div>
HTML;
    }

    private function processUpdate(): string
    {
        return <<<HTML
<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; color: #333;">
    <div style="background: #059669; padding: 20px; text-align: center;">
        <h1 style="color: #ffffff; margin: 0; font-size: 20px;">Actualização do Processo</h1>
    </div>

    <div style="padding: 30px 20px;">
        <p style="font-size: 16px;">Exmo(a). Sr(a). {%persons.name%},</p>

        <p style="font-size: 15px; line-height: 1.6;">
            Informamos que o seu processo jurídico foi actualizado. Seguem os detalhes mais recentes:
        </p>

        <table style="width: 100%; border-collapse: collapse; margin: 20px 0; background: #f0fdf4; border-radius: 8px;">
            <tr>
                <td style="padding: 10px 15px; font-weight: bold; color: #065f46; width: 40%;">Processo:</td>
                <td style="padding: 10px 15px;">{%leads.title%}</td>
            </tr>
            <tr style="background: #dcfce7;">
                <td style="padding: 10px 15px; font-weight: bold; color: #065f46;">Nº do Processo:</td>
                <td style="padding: 10px 15px;">{%leads.case_number%}</td>
            </tr>
            <tr>
                <td style="padding: 10px 15px; font-weight: bold; color: #065f46;">Fase Actual:</td>
                <td style="padding: 10px 15px;">{%leads.lead_pipeline_stage_id%}</td>
            </tr>
            <tr style="background: #dcfce7;">
                <td style="padding: 10px 15px; font-weight: bold; color: #065f46;">Advogado Responsável:</td>
                <td style="padding: 10px 15px;">{%users.name%}</td>
            </tr>
        </table>

        <p style="font-size: 15px; line-height: 1.6;">
            Para informações adicionais ou esclarecimento de dúvidas, por favor contacte o seu advogado responsável.
        </p>

        <p style="font-size: 14px; color: #666; margin-top: 30px; padding-top: 15px; border-top: 1px solid #e2e8f0;">
            Este é um e-mail automático enviado pelo sistema IAMI CRM.
        </p>
    </div>

    <div style="background: #059669; padding: 15px; text-align: center;">
        <p style="color: #a7f3d0; font-size: 12px; margin: 0;">
            © {%core.date%} — Sistema CRM Jurídico Angola
        </p>
    </div>
</div>
HTML;
    }

    private function deadlineAlert(): string
    {
        return <<<HTML
<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; color: #333;">
    <div style="background: #dc2626; padding: 20px; text-align: center;">
        <h1 style="color: #ffffff; margin: 0; font-size: 20px;">⚠️ ALERTA: Prazo Processual a Vencer</h1>
    </div>

    <div style="padding: 30px 20px;">
        <p style="font-size: 16px;">Caro(a) {%users.name%},</p>

        <div style="background: #fef2f2; border: 2px solid #dc2626; border-radius: 8px; padding: 20px; margin: 20px 0;">
            <h2 style="color: #991b1b; margin: 0 0 15px 0; font-size: 16px;">⚠️ Atenção: Prazo a Vencer</h2>
            <table style="width: 100%;">
                <tr>
                    <td style="color: #7f1d1d; font-weight: bold; padding: 5px 0; width: 40%;">Processo:</td>
                    <td style="padding: 5px 0;">{%leads.title%}</td>
                </tr>
                <tr>
                    <td style="color: #7f1d1d; font-weight: bold; padding: 5px 0;">Nº do Processo:</td>
                    <td style="padding: 5px 0;">{%leads.case_number%}</td>
                </tr>
                <tr>
                    <td style="color: #7f1d1d; font-weight: bold; padding: 5px 0;">Tribunal:</td>
                    <td style="padding: 5px 0;">{%leads.court%}</td>
                </tr>
                <tr>
                    <td style="color: #7f1d1d; font-weight: bold; padding: 5px 0;">Advogado:</td>
                    <td style="padding: 5px 0;">{%users.name%}</td>
                </tr>
            </table>
        </div>

        <p style="font-size: 15px; line-height: 1.6; color: #dc2626; font-weight: bold;">
            Por favor, tome as medidas necessárias para cumprir este prazo processual com urgência.
        </p>

        <p style="font-size: 14px; color: #666; margin-top: 30px; padding-top: 15px; border-top: 1px solid #e2e8f0;">
            Este é um alerta automático enviado pelo sistema IAMI CRM — Uso interno.
        </p>
    </div>

    <div style="background: #dc2626; padding: 15px; text-align: center;">
        <p style="color: #fecaca; font-size: 12px; margin: 0;">
            © {%core.date%} — Sistema CRM Jurídico Angola — Alerta Interno
        </p>
    </div>
</div>
HTML;
    }

    private function paymentReceipt(): string
    {
        return <<<HTML
<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; color: #333;">
    <div style="background: #1a365d; padding: 20px; text-align: center;">
        <h1 style="color: #ffffff; margin: 0; font-size: 20px;">✅ Confirmação de Pagamento</h1>
    </div>

    <div style="padding: 30px 20px;">
        <p style="font-size: 16px;">Exmo(a). Sr(a). {%persons.name%},</p>

        <p style="font-size: 15px; line-height: 1.6;">
            Confirmamos a recepção do seu pagamento referente aos serviços jurídicos prestados.
        </p>

        <div style="background: #f0fdf4; border: 1px solid #86efac; border-radius: 8px; padding: 20px; margin: 20px 0;">
            <h3 style="color: #166534; margin: 0 0 15px 0;">Detalhes do Pagamento</h3>
            <table style="width: 100%;">
                <tr>
                    <td style="color: #166534; font-weight: bold; padding: 6px 0; width: 45%;">Processo:</td>
                    <td style="padding: 6px 0;">{%leads.title%}</td>
                </tr>
                <tr>
                    <td style="color: #166534; font-weight: bold; padding: 6px 0;">Referência:</td>
                    <td style="padding: 6px 0;">{%quotes.subject%}</td>
                </tr>
                <tr>
                    <td style="color: #166534; font-weight: bold; padding: 6px 0;">Valor Recebido:</td>
                    <td style="padding: 6px 0; font-size: 18px; font-weight: bold; color: #15803d;">{%quotes.grand_total%} Kz</td>
                </tr>
                <tr>
                    <td style="color: #166534; font-weight: bold; padding: 6px 0;">IVA (14%):</td>
                    <td style="padding: 6px 0;">{%quotes.tax_amount%} Kz</td>
                </tr>
            </table>
        </div>

        <p style="font-size: 15px; line-height: 1.6;">
            Obrigado pela sua confiança. Continuamos ao seu dispor para qualquer questão jurídica.
        </p>

        <p style="font-size: 14px; color: #666; margin-top: 30px; padding-top: 15px; border-top: 1px solid #e2e8f0;">
            Este é um e-mail automático enviado pelo sistema IAMI CRM. Guarde este recibo para os seus registos.
        </p>
    </div>

    <div style="background: #1a365d; padding: 15px; text-align: center;">
        <p style="color: #a0aec0; font-size: 12px; margin: 0;">
            © {%core.date%} — Sistema CRM Jurídico Angola
        </p>
    </div>
</div>
HTML;
    }
}
