<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $fillable = ['type', 'name', 'subject', 'body'];

    public static array $types = [
        'confirmacao_reserva' => 'Confirmação de Reserva',
        'lembrete_pagamento' => 'Lembrete de Pagamento',
        'aviso_atraso' => 'Aviso de Atraso',
        'recibo_pagamento' => 'Recibo de Pagamento',
    ];

    public static array $placeholders = [
        '{client_name}' => 'Nome do cliente',
        '{tour_name}' => 'Nome do tour',
        '{tour_code}' => 'Código do tour',
        '{amount}' => 'Valor da parcela',
        '{due_date}' => 'Data de vencimento',
        '{payment_link}' => 'Link de pagamento',
        '{pix_instructions}' => 'Instruções de PIX',
        '{installment_number}' => 'Número da parcela',
        '{total_value}' => 'Valor total da reserva',
        '{currency}' => 'Moeda',
    ];
}
