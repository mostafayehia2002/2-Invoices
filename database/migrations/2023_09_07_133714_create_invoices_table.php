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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number');
            $table->date('invoice_date')->nullable();
            $table->date('due_date')->nullable();
            $table->string('product');
            $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete()->cascadeOnUpdate();
            $table->decimal('amount_collection',20,2);
            $table->decimal('amount_commission',20,2);
            $table->decimal('discount');
            $table->decimal('rate_vat');
            $table->decimal('value_vat',8,2);
            $table->decimal('total',8,2);
            $table->string('status',50)->default('غير مدفوعه');
            $table->boolean('value_status')->default('0');
            $table->text('note')->nullable();
            $table->string('created_by');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
