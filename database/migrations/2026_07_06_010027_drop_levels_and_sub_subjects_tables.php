<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('levels');
        Schema::dropIfExists('sub_subjects');
    }

    public function down(): void
    {
        // Intentionally left blank — old structure is not being restored.
    }
};