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
        Schema::create('fee_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('programme_name')->nullable();
            $table->string('batch')->nullable();
            $table->string('application_number')->nullable();
            $table->string('roll_no')->nullable();
            $table->string('student_id')->nullable();
            $table->string('section')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->date('dob')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('whatsapp_no')->nullable();
            $table->string('gender')->nullable();
            $table->string('category')->nullable();
            $table->string('transaction_type');
            $table->string('direction');
            $table->decimal('amount', 10, 2);
            $table->string('fee_month')->nullable();
            $table->string('description')->nullable();
            $table->string('payment_method');
            $table->date('transaction_date')->nullable();
            $table->string('reference_no')->nullable();
            $table->string('attachment_path')->nullable();
            $table->text('note')->nullable();
            $table->string('performed_by')->default('student');
            $table->text('remarks')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_transactions');
    }
};
