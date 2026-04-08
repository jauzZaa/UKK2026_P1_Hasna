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

        Schema::create('unit_conditions', function (Blueprint $table) {
            $table->string('id', 255)->primary()->comment('Kode unik riwayat kondisi  ,   dibuat BE');
            $table->string('unit_code', 255)->comment('FK ke tool_units');
            $table->foreign('unit_code')->references('code')->on('tool_units');
            $table->integer('return_id')->nullable()->comment('FK ke returns  ,   NULL jika dicatat di luar konteks pengembalian   (  entry awal  ,   maintenance  ,   inspeksi)');
            $table->enum('conditions', ["good","broken","maintenance"]);
            $table->text('notes')->comment('Penjelasan kondisi saat dicatat');
            $table->timestamp('recorded_at')->comment('Waktu kondisi dicatat. Kondisi terkini = recorded_at paling baru');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_conditions');
    }
};
