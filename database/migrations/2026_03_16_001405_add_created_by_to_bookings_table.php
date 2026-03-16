<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->after('notes')->constrained('users')->nullOnDelete();
        });

        // Set existing bookings to admin (user id 1)
        DB::table('bookings')->whereNull('created_by')->update(['created_by' => 1]);
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');
        });
    }
};
