<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use App\Models\ActivityLog;
use App\Helpers\PlaceholderHelper;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    public function index()
    {
        $templates = EmailTemplate::all()->groupBy('type');
        $types = EmailTemplate::$types;

        return view('email-templates.index', compact('templates', 'types'));
    }

    public function create()
    {
        $types = EmailTemplate::$types;
        $placeholders = EmailTemplate::$placeholders;

        return view('email-templates.create', compact('types', 'placeholders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type'    => 'required|in:' . implode(',', array_keys(EmailTemplate::$types)),
            'name'    => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body'    => 'required|string',
        ]);

        $template = EmailTemplate::create($validated);

        ActivityLog::log('criou template', 'EmailTemplate', $template->id, [
            'name' => $template->name,
            'type' => $template->type,
        ]);

        return redirect()->route('email-templates.index')->with('success', 'Template criado com sucesso.');
    }

    public function edit(EmailTemplate $emailTemplate)
    {
        $types = EmailTemplate::$types;
        $placeholders = EmailTemplate::$placeholders;

        return view('email-templates.edit', compact('emailTemplate', 'types', 'placeholders'));
    }

    public function update(Request $request, EmailTemplate $emailTemplate)
    {
        $validated = $request->validate([
            'type'    => 'required|in:' . implode(',', array_keys(EmailTemplate::$types)),
            'name'    => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body'    => 'required|string',
        ]);

        $emailTemplate->update($validated);

        ActivityLog::log('atualizou template', 'EmailTemplate', $emailTemplate->id, [
            'name' => $emailTemplate->name,
            'type' => $emailTemplate->type,
        ]);

        return redirect()->route('email-templates.index')->with('success', 'Template atualizado com sucesso.');
    }

    public function destroy(EmailTemplate $emailTemplate)
    {
        $details = [
            'name' => $emailTemplate->name,
            'type' => $emailTemplate->type,
        ];

        $emailTemplate->delete();

        ActivityLog::log('excluiu template', 'EmailTemplate', null, $details);

        return redirect()->route('email-templates.index')->with('success', 'Template excluído com sucesso.');
    }

    public function preview(EmailTemplate $emailTemplate)
    {
        $sampleData = [
            '{client_name}'        => 'Maria Silva',
            '{tour_name}'          => 'Tour Europa 2026',
            '{tour_code}'          => 'EUR-2026',
            '{amount}'             => '1.500,00',
            '{due_date}'           => '15/03/2026',
            '{payment_link}'       => 'https://exemplo.com/pagamento/12345',
            '{pix_instructions}'   => 'Chave PIX: exemplo@email.com',
            '{installment_number}' => '2',
            '{total_value}'        => '4.500,00',
            '{currency}'           => 'BRL',
        ];

        $subject = str_replace(array_keys($sampleData), array_values($sampleData), $emailTemplate->subject);
        $body = str_replace(array_keys($sampleData), array_values($sampleData), $emailTemplate->body);

        return view('email-templates.preview', compact('emailTemplate', 'subject', 'body'));
    }
}
