<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tours', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('type', ['grupo', 'privado', 'agencia', 'influencer']);
            $table->enum('default_currency', ['BRL', 'USD', 'EUR'])->default('BRL');
            $table->text('notes')->nullable();
            $table->enum('status', ['ativo', 'inativo'])->default('ativo');
            $table->integer('max_travelers')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tours');
    }
};
