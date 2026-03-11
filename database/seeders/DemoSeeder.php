<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Booking;
use App\Models\Client;
use App\Models\EmailLog;
use App\Models\EmailTemplate;
use App\Models\Installment;
use App\Models\Setting;
use App\Models\Tour;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // ===== TOURS =====
        $tours = [
            ['name' => 'Europa Clássica 2026', 'code' => 'EUR-CLA-2026', 'type' => 'grupo', 'default_currency' => 'EUR', 'status' => 'ativo', 'max_travelers' => 20, 'notes' => 'Paris, Roma, Barcelona - 15 dias'],
            ['name' => 'Patagônia Adventure 2026', 'code' => 'PAT-ADV-2026', 'type' => 'grupo', 'default_currency' => 'USD', 'status' => 'ativo', 'max_travelers' => 12, 'notes' => 'Torres del Paine, Glaciar Perito Moreno - 10 dias'],
            ['name' => 'Egito Milenar 2026', 'code' => 'EGI-MIL-2026', 'type' => 'grupo', 'default_currency' => 'USD', 'status' => 'ativo', 'max_travelers' => 15, 'notes' => 'Cairo, Luxor, cruzeiro no Nilo - 12 dias'],
            ['name' => 'Japão Sakura 2026', 'code' => 'JAP-SAK-2026', 'type' => 'grupo', 'default_currency' => 'USD', 'status' => 'ativo', 'max_travelers' => 16, 'notes' => 'Tokyo, Kyoto, Osaka - temporada de cerejeiras'],
            ['name' => 'Turquia Exclusiva 2026', 'code' => 'TUR-EXC-2026', 'type' => 'privado', 'default_currency' => 'EUR', 'status' => 'ativo', 'max_travelers' => 6, 'notes' => 'Istambul, Capadócia, Pamukkale - roteiro privado'],
            ['name' => 'Safari Tanzânia 2025', 'code' => 'SAF-TAN-2025', 'type' => 'grupo', 'default_currency' => 'USD', 'status' => 'inativo', 'max_travelers' => 10, 'notes' => 'Serengeti, Ngorongoro - tour finalizado'],
            ['name' => 'Influencer Bali Experience', 'code' => 'INF-BAL-2026', 'type' => 'influencer', 'default_currency' => 'USD', 'status' => 'ativo', 'max_travelers' => 4, 'notes' => 'Pacote para influenciadores - Bali e Nusa Penida'],
            ['name' => 'Agência Parceira - Maldivas', 'code' => 'AGN-MAL-2026', 'type' => 'agencia', 'default_currency' => 'USD', 'status' => 'ativo', 'max_travelers' => null, 'notes' => 'Pacote operado via agência parceira'],
        ];

        foreach ($tours as $tour) {
            Tour::create($tour);
        }

        // ===== CLIENTS =====
        $clients = [
            ['name' => 'Maria Silva', 'email' => 'maria.silva@email.com', 'notes' => 'Cliente frequente, prefere parcelamento'],
            ['name' => 'João Santos', 'email' => 'joao.santos@email.com', 'notes' => 'Primeira viagem internacional'],
            ['name' => 'Ana Costa', 'email' => 'ana.costa@email.com', 'notes' => 'Viaja com marido, prefere tours privados'],
            ['name' => 'Pedro Oliveira', 'email' => 'pedro.oliveira@email.com', 'notes' => null],
            ['name' => 'Luciana Ferreira', 'email' => 'luciana.ferreira@email.com', 'notes' => 'Influenciadora digital - @luciana.viaja'],
            ['name' => 'Carlos Mendes', 'email' => 'carlos.mendes@email.com', 'notes' => 'Sempre paga via PIX'],
            ['name' => 'Beatriz Lima', 'email' => 'beatriz.lima@email.com', 'notes' => 'Agente de viagem parceira'],
            ['name' => 'Roberto Alves', 'email' => 'roberto.alves@email.com', 'notes' => null],
            ['name' => 'Fernanda Rocha', 'email' => 'fernanda.rocha@email.com', 'notes' => 'Viaja com família (4 pessoas)'],
            ['name' => 'Gustavo Pereira', 'email' => 'gustavo.pereira@email.com', 'notes' => 'Prefere pagamento via Wise'],
        ];

        foreach ($clients as $client) {
            Client::create($client);
        }

        $today = Carbon::today();

        // ===== BOOKINGS & INSTALLMENTS =====

        // Booking 1: Maria - Europa (confirmado, parcelas mistas)
        $b1 = Booking::create([
            'client_id' => 1, 'tour_id' => 1, 'start_date' => $today->copy()->addMonths(3),
            'currency' => 'EUR', 'total_value' => 4500.00, 'num_travelers' => 2,
            'status' => 'confirmado', 'notes' => 'Casal - quarto duplo',
        ]);
        Installment::create(['booking_id' => $b1->id, 'installment_number' => 1, 'amount' => 1500.00, 'due_date' => $today->copy()->subDays(10), 'status' => 'pago', 'payment_method' => 'pix', 'paid_at' => $today->copy()->subDays(12)]);
        Installment::create(['booking_id' => $b1->id, 'installment_number' => 2, 'amount' => 1500.00, 'due_date' => $today->copy()->addDays(3), 'status' => 'pendente', 'payment_method' => 'link', 'payment_link' => 'https://pay.example.com/maria-eur-2']);
        Installment::create(['booking_id' => $b1->id, 'installment_number' => 3, 'amount' => 1500.00, 'due_date' => $today->copy()->addMonth(), 'status' => 'pendente', 'payment_method' => 'link']);

        // Booking 2: João - Patagônia (pendente, atrasado)
        $b2 = Booking::create([
            'client_id' => 2, 'tour_id' => 2, 'start_date' => $today->copy()->addMonths(2),
            'currency' => 'USD', 'total_value' => 3200.00, 'num_travelers' => 1,
            'status' => 'pendente', 'discount_notes' => '10% desconto primeira viagem',
        ]);
        Installment::create(['booking_id' => $b2->id, 'installment_number' => 1, 'amount' => 1600.00, 'due_date' => $today->copy()->subDays(5), 'status' => 'atrasado', 'payment_method' => 'link', 'payment_link' => 'https://pay.example.com/joao-usd-1']);
        Installment::create(['booking_id' => $b2->id, 'installment_number' => 2, 'amount' => 1600.00, 'due_date' => $today->copy()->addDays(25), 'status' => 'pendente', 'payment_method' => 'link', 'payment_link' => 'https://pay.example.com/joao-usd-2']);

        // Booking 3: Ana - Turquia privado (confirmado, pago)
        $b3 = Booking::create([
            'client_id' => 3, 'tour_id' => 5, 'start_date' => $today->copy()->addMonths(4),
            'currency' => 'EUR', 'total_value' => 8000.00, 'num_travelers' => 2,
            'status' => 'confirmado', 'notes' => 'Tour privado - casal',
        ]);
        Installment::create(['booking_id' => $b3->id, 'installment_number' => 1, 'amount' => 4000.00, 'due_date' => $today->copy()->subMonth(), 'status' => 'pago', 'payment_method' => 'wise', 'paid_at' => $today->copy()->subMonth()]);
        Installment::create(['booking_id' => $b3->id, 'installment_number' => 2, 'amount' => 4000.00, 'due_date' => $today->copy()->addDays(7), 'status' => 'pendente', 'payment_method' => 'wise']);

        // Booking 4: Pedro - Egito (pendente, falta link)
        $b4 = Booking::create([
            'client_id' => 4, 'tour_id' => 3, 'start_date' => $today->copy()->addMonths(5),
            'currency' => 'USD', 'total_value' => 2800.00, 'num_travelers' => 1,
            'status' => 'pendente',
        ]);
        Installment::create(['booking_id' => $b4->id, 'installment_number' => 1, 'amount' => 1400.00, 'due_date' => $today->copy()->addDays(5), 'status' => 'falta_link', 'payment_method' => 'link']);
        Installment::create(['booking_id' => $b4->id, 'installment_number' => 2, 'amount' => 1400.00, 'due_date' => $today->copy()->addMonths(2), 'status' => 'pendente', 'payment_method' => 'link']);

        // Booking 5: Luciana - Bali Influencer (confirmado)
        $b5 = Booking::create([
            'client_id' => 5, 'tour_id' => 7, 'start_date' => $today->copy()->addMonths(2),
            'currency' => 'USD', 'total_value' => 1500.00, 'num_travelers' => 1,
            'status' => 'confirmado', 'notes' => 'Pacote influencer com conteúdo obrigatório',
        ]);
        Installment::create(['booking_id' => $b5->id, 'installment_number' => 1, 'amount' => 750.00, 'due_date' => $today->copy()->subDays(3), 'status' => 'pago', 'payment_method' => 'pix', 'paid_at' => $today->copy()->subDays(4)]);
        Installment::create(['booking_id' => $b5->id, 'installment_number' => 2, 'amount' => 750.00, 'due_date' => $today->copy()->addDays(14), 'status' => 'pendente', 'payment_method' => 'pix']);

        // Booking 6: Carlos - Europa (pago completo)
        $b6 = Booking::create([
            'client_id' => 6, 'tour_id' => 1, 'start_date' => $today->copy()->addMonths(3),
            'currency' => 'EUR', 'total_value' => 2200.00, 'num_travelers' => 1,
            'status' => 'confirmado', 'notes' => 'Pagou tudo via PIX adiantado',
        ]);
        Installment::create(['booking_id' => $b6->id, 'installment_number' => 1, 'amount' => 2200.00, 'due_date' => $today->copy()->subWeeks(2), 'status' => 'pago', 'payment_method' => 'pix', 'paid_at' => $today->copy()->subWeeks(3)]);

        // Booking 7: Fernanda - Japão (4 viajantes, pendente)
        $b7 = Booking::create([
            'client_id' => 9, 'tour_id' => 4, 'start_date' => $today->copy()->addMonths(6),
            'currency' => 'USD', 'total_value' => 12000.00, 'num_travelers' => 4,
            'status' => 'pendente', 'notes' => 'Família - 2 adultos + 2 crianças',
        ]);
        Installment::create(['booking_id' => $b7->id, 'installment_number' => 1, 'amount' => 3000.00, 'due_date' => $today->copy()->addDays(1), 'status' => 'pendente', 'payment_method' => 'link', 'payment_link' => 'https://pay.example.com/fernanda-1']);
        Installment::create(['booking_id' => $b7->id, 'installment_number' => 2, 'amount' => 3000.00, 'due_date' => $today->copy()->addMonth(), 'status' => 'pendente', 'payment_method' => 'link']);
        Installment::create(['booking_id' => $b7->id, 'installment_number' => 3, 'amount' => 3000.00, 'due_date' => $today->copy()->addMonths(2), 'status' => 'pendente', 'payment_method' => 'link']);
        Installment::create(['booking_id' => $b7->id, 'installment_number' => 4, 'amount' => 3000.00, 'due_date' => $today->copy()->addMonths(3), 'status' => 'pendente', 'payment_method' => 'link']);

        // Booking 8: Gustavo - Patagônia (wise, vence hoje)
        $b8 = Booking::create([
            'client_id' => 10, 'tour_id' => 2, 'start_date' => $today->copy()->addMonths(2),
            'currency' => 'USD', 'total_value' => 3200.00, 'num_travelers' => 1,
            'status' => 'confirmado',
        ]);
        Installment::create(['booking_id' => $b8->id, 'installment_number' => 1, 'amount' => 1600.00, 'due_date' => $today, 'status' => 'pendente', 'payment_method' => 'wise']);
        Installment::create(['booking_id' => $b8->id, 'installment_number' => 2, 'amount' => 1600.00, 'due_date' => $today->copy()->addMonth(), 'status' => 'pendente', 'payment_method' => 'wise']);

        // Booking 9: Manual tour entry
        $b9 = Booking::create([
            'client_id' => 8, 'tour_id' => null, 'tour_manual' => 'Roteiro Personalizado - Grécia 2026',
            'start_date' => $today->copy()->addMonths(5), 'currency' => 'EUR',
            'total_value' => 5500.00, 'num_travelers' => 2, 'status' => 'pendente',
            'notes' => 'Tour sob medida - Atenas, Santorini, Mykonos',
        ]);
        Installment::create(['booking_id' => $b9->id, 'installment_number' => 1, 'amount' => 1833.33, 'due_date' => $today->copy()->addDays(10), 'status' => 'pendente', 'payment_method' => 'link', 'payment_link' => 'https://pay.example.com/roberto-1']);
        Installment::create(['booking_id' => $b9->id, 'installment_number' => 2, 'amount' => 1833.33, 'due_date' => $today->copy()->addMonths(2), 'status' => 'pendente', 'payment_method' => 'link']);
        Installment::create(['booking_id' => $b9->id, 'installment_number' => 3, 'amount' => 1833.34, 'due_date' => $today->copy()->addMonths(3), 'status' => 'pendente', 'payment_method' => 'link']);

        // Booking 10: Safari concluído
        $b10 = Booking::create([
            'client_id' => 3, 'tour_id' => 6, 'start_date' => $today->copy()->subMonths(2),
            'currency' => 'USD', 'total_value' => 4000.00, 'num_travelers' => 2,
            'status' => 'concluido', 'notes' => 'Tour finalizado com sucesso',
        ]);
        Installment::create(['booking_id' => $b10->id, 'installment_number' => 1, 'amount' => 2000.00, 'due_date' => $today->copy()->subMonths(4), 'status' => 'pago', 'payment_method' => 'wise', 'paid_at' => $today->copy()->subMonths(4)]);
        Installment::create(['booking_id' => $b10->id, 'installment_number' => 2, 'amount' => 2000.00, 'due_date' => $today->copy()->subMonths(3), 'status' => 'pago', 'payment_method' => 'wise', 'paid_at' => $today->copy()->subMonths(3)]);

        // ===== EMAIL TEMPLATES =====
        EmailTemplate::create([
            'type' => 'confirmacao_reserva',
            'name' => 'Confirmação de Reserva Padrão',
            'subject' => 'Reserva Confirmada - {tour_name}',
            'body' => "Olá {client_name},\n\nSua reserva para o tour {tour_name} ({tour_code}) foi confirmada!\n\nDetalhes:\n- Valor total: {currency} {total_value}\n- Viajantes: sua reserva está confirmada\n\nEm breve enviaremos as informações de pagamento.\n\nAtenciosamente,\nEquipe de Turismo",
        ]);

        EmailTemplate::create([
            'type' => 'lembrete_pagamento',
            'name' => 'Lembrete de Pagamento',
            'subject' => 'Lembrete: Parcela {installment_number} - {tour_name}',
            'body' => "Olá {client_name},\n\nEste é um lembrete sobre a parcela {installment_number} do seu tour {tour_name}.\n\nValor: {currency} {amount}\nVencimento: {due_date}\n\n{payment_link}\n{pix_instructions}\n\nQualquer dúvida, entre em contato conosco.\n\nAtenciosamente,\nEquipe de Turismo",
        ]);

        EmailTemplate::create([
            'type' => 'aviso_atraso',
            'name' => 'Aviso de Atraso',
            'subject' => 'URGENTE: Parcela em Atraso - {tour_name}',
            'body' => "Olá {client_name},\n\nIdentificamos que a parcela {installment_number} do tour {tour_name} está em atraso.\n\nValor: {currency} {amount}\nVencimento: {due_date}\n\nPor favor, regularize o pagamento o mais breve possível.\n\n{payment_link}\n{pix_instructions}\n\nAtenciosamente,\nEquipe de Turismo",
        ]);

        EmailTemplate::create([
            'type' => 'recibo_pagamento',
            'name' => 'Recibo de Pagamento',
            'subject' => 'Pagamento Recebido - Parcela {installment_number} - {tour_name}',
            'body' => "Olá {client_name},\n\nConfirmamos o recebimento do pagamento da parcela {installment_number} do tour {tour_name}.\n\nValor: {currency} {amount}\n\nObrigado!\n\nAtenciosamente,\nEquipe de Turismo",
        ]);

        // ===== SETTINGS =====
        Setting::set('msg_link', 'Clique no link abaixo para realizar o pagamento:', 'payment_messages');
        Setting::set('msg_pix', 'Realize o pagamento via PIX utilizando os dados abaixo:', 'payment_messages');
        Setting::set('msg_wise', 'Realize a transferência internacional via Wise para a conta indicada:', 'payment_messages');
        Setting::set('pix_instructions', "Chave PIX: email@agencia.com\nBanco: Nubank\nTitular: Agência de Turismo LTDA\nCNPJ: 12.345.678/0001-00", 'pix');
        Setting::set('auto_7days_before', '1', 'automation');
        Setting::set('auto_3days_before', '1', 'automation');
        Setting::set('auto_due_date', '1', 'automation');
        Setting::set('auto_1day_after', '1', 'automation');
        Setting::set('auto_7days_after', '1', 'automation');
        Setting::set('smtp_host', 'smtp.gmail.com', 'smtp');
        Setting::set('smtp_port', '587', 'smtp');
        Setting::set('smtp_encryption', 'tls', 'smtp');
        Setting::set('smtp_from_name', 'Agência de Turismo', 'smtp');
        Setting::set('smtp_from_email', 'contato@agencia.com', 'smtp');
        Setting::set('cron_schedule', '0 8 * * * (diariamente às 08:00)', 'cron');

        // ===== SAMPLE EMAIL LOGS =====
        EmailLog::create([
            'installment_id' => 1, 'client_id' => 1, 'template_id' => 2,
            'subject' => 'Lembrete: Parcela 1 - Europa Clássica 2026',
            'body' => 'Lembrete de pagamento enviado automaticamente',
            'status' => 'enviado', 'trigger_type' => 'automatico',
            'sent_at' => $today->copy()->subDays(17),
        ]);
        EmailLog::create([
            'installment_id' => 3, 'client_id' => 2, 'template_id' => 3,
            'subject' => 'URGENTE: Parcela em Atraso - Patagônia Adventure 2026',
            'body' => 'Aviso de atraso enviado automaticamente',
            'status' => 'enviado', 'trigger_type' => 'automatico',
            'sent_at' => $today->copy()->subDays(4),
        ]);
        EmailLog::create([
            'installment_id' => 1, 'client_id' => 1, 'template_id' => 4,
            'subject' => 'Pagamento Recebido - Parcela 1 - Europa Clássica 2026',
            'body' => 'Recibo de pagamento enviado manualmente',
            'status' => 'enviado', 'trigger_type' => 'manual',
            'sent_at' => $today->copy()->subDays(12),
        ]);

        // ===== SAMPLE ACTIVITY LOGS =====
        ActivityLog::log('Reserva criada', 'Booking', $b1->id, ['client' => 'Maria Silva', 'tour' => 'Europa Clássica 2026']);
        ActivityLog::log('Pagamento registrado', 'Installment', 1, ['valor' => '1500.00', 'método' => 'pix']);
        ActivityLog::log('Tour criado', 'Tour', 1, ['nome' => 'Europa Clássica 2026']);
        ActivityLog::log('Cliente cadastrado', 'Client', 1, ['nome' => 'Maria Silva']);
        ActivityLog::log('E-mail reenviado', 'Installment', 3, ['template' => 'Aviso de Atraso', 'cliente' => 'João Santos']);
    }
}
