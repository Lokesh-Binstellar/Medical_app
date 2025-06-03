<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('popular_lab_tests', function (Blueprint $table) {
            $table->unsignedBigInteger('test_id')->after('id'); // or place it after another column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('popular_lab_tests', function (Blueprint $table) {
            $table->dropColumn('test_id');
        });
    }
};
