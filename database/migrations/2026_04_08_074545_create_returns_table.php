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
        Schema::disableForeignKeyConstraints();

        Schema::create('returns', function (Blueprint $table) {
            $table->integer('id')->primary()->autoIncrement();
            $table->integer('loan_id')->unique()->comment('FK ke loans  ,   1 loan hanya bisa punya 1 return');
            $table->foreign('loan_id')->references('id')->on('loans');
            $table->integer('employee_id')->comment('FK ke users   (  Employee  )   yang mencatat pengembalian');
            $table->foreign('employee_id')->references('id')->on('users');
            $table->string('condition_id', 255)->comment('FK ke unit_conditions  ,   kondisi alat saat dikembalikan');
            $table->foreign('condition_id')->references('id')->on('unit_conditions');
            $table->date('return_date')->comment('Tanggal aktual alat dikembalikan');
            $table->text('notes')->nullable()->comment('Catatan pengembalian dari Employee');
            $table->timestamp('created_at');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('returns');
    }
};
