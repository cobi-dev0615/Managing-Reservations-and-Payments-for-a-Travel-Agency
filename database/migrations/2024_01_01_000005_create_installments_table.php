<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->integer('installment_number');
            $table->decimal('amount', 10, 2);
            $table->date('due_date');
            $table->enum('status', ['pendente', 'pago', 'atrasado', 'falta_link'])->default('pendente');
            $table->enum('payment_method', ['link', 'pix', 'wise'])->default('link');
            $table->string('payment_link')->nullable();
            $table->datetime('paid_at')->nullable();
            $table->datetime('last_email_sent_at')->nullable();
            $table->foreignId('last_email_template_id')->nullable()->constrained('email_templates')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('installments');
    }
};
