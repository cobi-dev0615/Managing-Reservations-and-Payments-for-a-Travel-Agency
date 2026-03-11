<?php

namespace App\Helpers;

use App\Models\Installment;
use App\Models\Setting;

class PlaceholderHelper
{
    public static function replace(string $text, Installment $installment): string
    {
        $booking = $installment->booking;
        $client = $booking->client;
        $tour = $booking->tour;

        $replacements = [
            '{client_name}' => $client->name ?? '',
            '{tour_name}' => $booking->tour_name,
            '{tour_code}' => $tour->code ?? '',
            '{amount}' => number_format($installment->amount, 2, ',', '.'),
            '{due_date}' => $installment->due_date->format('d/m/Y'),
            '{payment_link}' => $installment->payment_link ?? '',
            '{pix_instructions}' => Setting::get('pix_instructions', ''),
            '{installment_number}' => $installment->installment_number,
            '{total_value}' => number_format($booking->total_value, 2, ',', '.'),
            '{currency}' => $booking->currency,
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $text);
    }
}
