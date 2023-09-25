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
        Schema::create('invoices_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('invoice_number',50);
            $table->string('product',50);
            $table->string('section',50);
            $table->string('status',50)->default('غير مدفوعه');
            $table->boolean('value_status')->default('0');
            $table->date('payment_date')->nullable();
            $table->string('created_by',255);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices_details');
    }
};
