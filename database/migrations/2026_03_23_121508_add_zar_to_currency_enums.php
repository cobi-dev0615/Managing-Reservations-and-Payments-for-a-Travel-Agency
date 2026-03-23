<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE bookings MODIFY COLUMN currency ENUM('BRL', 'USD', 'EUR', 'ZAR') DEFAULT 'BRL'");
        DB::statement("ALTER TABLE tours MODIFY COLUMN default_currency ENUM('BRL', 'USD', 'EUR', 'ZAR') DEFAULT 'BRL'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE bookings MODIFY COLUMN currency ENUM('BRL', 'USD', 'EUR') DEFAULT 'BRL'");
        DB::statement("ALTER TABLE tours MODIFY COLUMN default_currency ENUM('BRL', 'USD', 'EUR') DEFAULT 'BRL'");
    }
};
