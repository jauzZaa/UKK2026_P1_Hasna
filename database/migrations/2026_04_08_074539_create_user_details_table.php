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

        Schema::create('user_details', function (Blueprint $table) {
            $table->string('nik', 255)->primary()->comment('Nomor Induk Kependudukan  ,   unik per orang');
            $table->integer('user_id')->nullable()->comment('FK ke users');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('name', 255)->nullable()->comment('Nama lengkap');
            $table->string('no_hp', 255)->nullable()->comment('Nomor handphone');
            $table->string('address', 255)->nullable()->comment('Alamat lengkap');
            $table->date('birth_date')->nullable()->comment('Tanggal lahir');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_details');
    }
};
