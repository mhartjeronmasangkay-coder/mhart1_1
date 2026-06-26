<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_subject_id')->constrained();
            $table->integer('level_number');
            $table->string('name');
            $table->boolean('is_locked')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        
        Schema::dropIfExists('levels');
    }
};