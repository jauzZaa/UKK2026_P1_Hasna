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

        Schema::create('tool_units', function (Blueprint $table) {
            $table->string('code', 255)->primary()->comment('Kode unik unit fisik  ,   dibuat BE. Single: LPT-001 | Bundle: SET-PK-001');
            $table->integer('tool_id')->comment('FK ke tools   (  template)');
            $table->foreign('tool_id')->references('id')->on('tools');
            $table->enum('status', ["available","nonactive","lent"]);
            $table->text('notes')->comment('Catatan tambahan unit');
            $table->timestamp('created_at');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tool_units');
    }
};
