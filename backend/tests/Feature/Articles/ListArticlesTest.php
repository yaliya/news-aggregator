<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListArticlesTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_articles_with_basic_filters(): void
    {
        Article::factory()->create([
            'source' => 'newsapi',
            'category' => 'technology',
        ]);

        $response = $this->getJson('/api/articles?source=newsapi&category=technology');

        $response->assertOk()
            ->assertJsonStructure(['data', 'links', 'meta']);
    }
}

