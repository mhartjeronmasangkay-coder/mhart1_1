<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::dropIfExists('answers');
    Schema::dropIfExists('questions');

    Schema::create('questions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('question_group_id')->constrained()->cascadeOnDelete();
        $table->text('question_text');
        $table->timestamps();
    });
}
};