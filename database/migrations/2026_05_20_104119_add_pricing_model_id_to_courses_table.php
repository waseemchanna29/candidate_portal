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
        Schema::table('courses', function (Blueprint $table) {
                   $table->foreignId('pricing_model_id')->nullable()->constrained()->nullOnDelete()->after('is_active');
     
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
              $table->dropForeign(['pricing_model_id']);
            $table->dropColumn('pricing_model_id');
        });
    }
};
