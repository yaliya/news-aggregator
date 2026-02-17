<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('source'); // e.g. newsapi, guardian, nytimes
            $table->text('source_id'); // ID from the external provider
            $table->text('title');
            $table->text('content')->nullable();
            $table->text('description')->nullable();
            $table->string('author')->nullable();
            $table->string('category')->nullable();
            $table->text('url');
            $table->text('image_url')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('fetched_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->unique(['source', 'source_id']);
            $table->index('published_at');
            $table->index('category');
        });

        // PostgreSQL full-text search index on title + content
        DB::statement("
            CREATE INDEX articles_fulltext_idx
            ON articles
            USING GIN (to_tsvector('english', coalesce(title, '') || ' ' || coalesce(content, '')))
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the full-text index explicitly (PostgreSQL)
        DB::statement('DROP INDEX IF EXISTS articles_fulltext_idx');

        Schema::dropIfExists('articles');
    }
};

