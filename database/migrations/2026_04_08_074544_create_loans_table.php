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

        Schema::create('loans', function (Blueprint $table) {
            $table->integer('id')->primary()->autoIncrement();
            $table->integer('user_id')->comment('FK ke users  ,   peminjam yang mengajukan');
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('tool_id')->comment('FK ke tools  ,   template alat yang dipinjam');
            $table->foreign('tool_id')->references('id')->on('tools');
            $table->string('unit_code', 255)->comment('FK ke tool_units  ,   unit fisik spesifik yang dipilih user   (  berlaku untuk single maupun bundle)');
            $table->foreign('unit_code')->references('code')->on('tool_units');
            $table->integer('employee_id')->nullable()->comment('FK ke users   (  Employee  )    ,   diisi saat approve atau reject');
            $table->foreign('employee_id')->references('id')->on('users');
            $table->enum('status', ["pending","active","rejected","closed"]);
            $table->date('loan_date')->comment('Tanggal mulai peminjaman');
            $table->date('due_date')->comment('Tanggal wajib kembali');
            $table->text('purpose')->comment('Tujuan/keperluan peminjaman dari user');
            $table->text('notes')->nullable()->comment('Catatan Employee saat approve atau reject');
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->nullable();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
