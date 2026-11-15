<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Fix tabel orders
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                // Hapus kolom lama jika ada
                if (Schema::hasColumn('orders', 'total_price')) {
                    $table->dropColumn('total_price');
                }
                
                // Tambah kolom yang kurang
                if (!Schema::hasColumn('orders', 'total_amount')) {
                    $table->decimal('total_amount', 10, 2)->default(0);
                }
                if (!Schema::hasColumn('orders', 'payment_method')) {
                    $table->string('payment_method')->nullable();
                }
                if (!Schema::hasColumn('orders', 'shipping_address')) {
                    $table->text('shipping_address')->nullable();
                }
                if (!Schema::hasColumn('orders', 'payment_status')) {
                    $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
                }
                if (!Schema::hasColumn('orders', 'snap_token')) {
                    $table->string('snap_token')->nullable();
                }
            });
        }

        // Fix tabel contacts
        if (Schema::hasTable('contacts')) {
            Schema::table('contacts', function (Blueprint $table) {
                if (!Schema::hasColumn('contacts', 'name')) {
                    $table->string('name')->nullable()->after('user_id');
                }
                if (!Schema::hasColumn('contacts', 'admin_reply')) {
                    $table->text('admin_reply')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        // Rollback logic here if needed
    }
};