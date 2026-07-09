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
        Schema::create('handbook_articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('handbook_category_id')
                  ->constrained('handbook_categories')
                  ->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content')->nullable();   // HTML dari Quill.js
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->foreignId('author_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('handbook_articles');
    }
};
