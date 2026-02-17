<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserFeedTest extends TestCase
{
    use RefreshDatabase;

    public function test_feed_returns_articles_matching_user_preferences(): void
    {
        $user = User::factory()->create([
            'preferences' => [
                'source'   => ['newsapi'],
                'category' => ['technology'],
                'author'   => ['John Doe'],
            ],
        ]);

        Article::factory()->create([
            'source'   => 'newsapi',
            'category' => 'technology',
            'author'   => 'John Doe',
        ]);

        Article::factory()->create([
            'source'   => 'guardian',
            'category' => 'sports',
            'author'   => 'Jane Smith',
        ]);

        $response = $this->actingAs($user, 'api')->getJson('/api/user/feed');

        $response->assertOk()->assertJsonStructure(['data', 'links', 'meta'])->assertJsonCount(1, 'data');
    }

    public function test_feed_returns_empty_when_no_articles_match_preferences(): void
    {
        $user = User::factory()->create([
            'preferences' => [
                'source' => ['newsapi'],
            ],
        ]);

        Article::factory()->create(['source' => 'guardian']);

        $response = $this->actingAs($user, 'api')->getJson('/api/user/feed');

        $response->assertOk()->assertJsonCount(0, 'data');
    }

    public function test_feed_returns_all_articles_when_preferences_are_empty(): void
    {
        $user = User::factory()->create([
            'preferences' => [
                'source' => ['newsapi'],
            ],
        ]);

        Article::factory()->count(3)->create(['source' => 'newsapi']);

        $response = $this->actingAs($user, 'api')->getJson('/api/user/feed');

        $response->assertOk()->assertJsonCount(3, 'data');
    }

    public function test_feed_requires_authentication(): void
    {
        $response = $this->getJson('/api/user/feed');

        $response->assertUnauthorized();
    }

    public function test_feed_respects_per_page_parameter(): void
    {
        $user = User::factory()->create([
            'preferences' => [
                'source' => ['newsapi'],
            ],
        ]);

        Article::factory()->count(10)->create(['source' => 'newsapi']);

        $response = $this->actingAs($user, 'api')->getJson('/api/user/feed?per_page=3');

        $response->assertOk()->assertJsonCount(3, 'data');
    }
}
