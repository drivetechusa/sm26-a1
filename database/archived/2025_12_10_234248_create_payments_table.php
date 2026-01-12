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
            $table->decimal('amount', 10, 2);
            $table->dateTime('date');
            $table->integer('student_id')->nullable();
            $table->string('type')->nullable();
            $table->string('check_number')->nullable();
            $table->string('auth_number')->nullable();
            $table->integer('creditcards_id')->nullable();
            $table->integer('employee_id')->nullable();
            $table->dateTime('last_update')->nullable();
            $table->integer('updated_by')->nullable();
            $table->string('remarks')->nullable();
            $table->integer('last_four')->nullable();
            $table->timestamps();

            $table->index('student_id');
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
