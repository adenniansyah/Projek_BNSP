<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('orders', function (Blueprint $table) {
            // Tambahkan snap_token setelah payment_method (karena payment_method sudah ada)
            $table->string('snap_token')->nullable()->after('payment_method');
            
            // Tambahkan payment_status setelah snap_token
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'expired'])
                  ->default('pending')
                  ->after('snap_token');
        });
    }

    public function down(): void {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['snap_token', 'payment_status']);
        });
    }
};