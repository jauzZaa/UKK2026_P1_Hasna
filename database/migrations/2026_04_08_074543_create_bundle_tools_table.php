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

        Schema::create('bundle_tools', function (Blueprint $table) {
            $table->integer('id')->primary()->autoIncrement();
            $table->integer('bundle_id')->comment('FK ke tools dimana item_type = bundle');
            $table->foreign('bundle_id')->references('id')->on('tools');
            $table->integer('tool_id')->comment('FK ke tools dimana item_type = bundle_tool');
            $table->foreign('tool_id')->references('id')->on('tools');
            $table->integer('qty')->comment('Jumlah sub-tool ini dalam satu bundle');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bundle_tools');
    }
};
