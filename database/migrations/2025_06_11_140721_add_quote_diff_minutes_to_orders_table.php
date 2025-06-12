<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('quote_diff_minutes')->nullable()->after('commission');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'quote_diff_minutes')) {
                $table->dropColumn('quote_diff_minutes');
            }
        });
    }
};
