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
        Schema::create('files', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->nullableMorphs('item');
            $table->uuid('product_id')->nullable();
            $table->unsignedTinyInteger('type')->index();
            $table->string('name');
            $table->string('path');
            $table->longText('md5');
            $table->integer('size');
            $table->string('mime_type');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
