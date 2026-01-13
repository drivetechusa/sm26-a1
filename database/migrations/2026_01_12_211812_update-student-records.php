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
        Schema::table('students', function (Blueprint $table) {
            $table->string('parent_relationship')->nullable();
            $table->string('parent_alternate_relationship')->nullable();
            $table->boolean('permit_verified')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student', function (Blueprint $table) {
            $table->dropColumn('parent_relationship');
            $table->dropColumn('parent_alternate_relationship');
            $table->dropColumn('permit_verified');
        });
    }
};
