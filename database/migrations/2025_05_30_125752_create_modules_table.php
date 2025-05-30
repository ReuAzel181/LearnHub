<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon')->nullable();
            $table->text('note')->nullable();
            $table->string('file_path')->nullable();
            $table->string('category_color')->nullable();
            $table->date('deadline')->nullable();
            $table->timestamps();
            $table->softDeletes(); // For the recycle bin feature
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
}; 