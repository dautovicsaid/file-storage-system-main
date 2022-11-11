<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('extension')->nullable()->default(null);
            $table->string('path')->nullable()->default(null);
            $table->integer('size')->nullable()->default(null);
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('modified_by')->constrained('users');
            $table->foreignId('folder_id')->nullable()->constrained('files')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
};
