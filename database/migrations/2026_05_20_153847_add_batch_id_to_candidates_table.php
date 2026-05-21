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
        Schema::table('candidates', function (Blueprint $table) {
             $table->foreignId('batch_id')->nullable()->constrained()->nullOnDelete()->after('course_id');
            $table->boolean('is_waitlisted')->default(false)->after('batch_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
             $table->dropForeign(['batch_id']);
            $table->dropColumn(['batch_id', 'is_waitlisted']);
        });
    }
};
