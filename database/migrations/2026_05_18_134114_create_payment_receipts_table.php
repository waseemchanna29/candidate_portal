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
        Schema::create('payment_receipts', function (Blueprint $table) {
            $table->id();
               $table->foreignId('candidate_id')->constrained()->onDelete('cascade');
            $table->string('receipt_number');
            $table->decimal('amount', 10, 2);
            $table->string('bank_name');
            $table->date('payment_date');
            $table->string('receipt_image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_receipts');
    }
};
