<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('tour_id')->nullable()->constrained()->onDelete('set null');
            $table->string('tour_manual')->nullable();
            $table->date('start_date');
            $table->enum('currency', ['BRL', 'USD', 'EUR'])->default('BRL');
            $table->decimal('total_value', 10, 2);
            $table->text('discount_notes')->nullable();
            $table->integer('num_travelers')->default(1);
            $table->enum('status', ['confirmado', 'pendente', 'cancelado', 'concluido'])->default('pendente');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
