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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(); //name
            $table->string('email')->nullable(); //email
            $table->string('phone')->nullable(); //phone
            $table->string('address')->nullable(); //address
            $table->string('cash_delivery')->nullable(); //cash delivery
            $table->string('total_amount')->nullable(); //total amount
            $table->string('payment_type')->nullable(); //payment type
            $table->string('invoice_no')->nullable(); //invoice number
            $table->string('order_date')->nullable(); //order date
            $table->string('order_month')->nullable(); //order month
            $table->string('order_year')->nullable(); //order year
            $table->string('status')->nullable(); //status
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
