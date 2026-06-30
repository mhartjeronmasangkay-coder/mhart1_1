<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Make columns nullable to support partial updates
        DB::statement('ALTER TABLE subjects MODIFY COLUMN icon VARCHAR(255) DEFAULT "📚" NULL');
        DB::statement('ALTER TABLE subjects MODIFY COLUMN color VARCHAR(255) DEFAULT "#007bff" NULL');
        DB::statement('ALTER TABLE subjects MODIFY COLUMN description LONGTEXT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE subjects MODIFY COLUMN icon VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE subjects MODIFY COLUMN color VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE subjects MODIFY COLUMN description LONGTEXT NOT NULL');
    }
};
