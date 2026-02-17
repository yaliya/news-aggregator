<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'source'       => $this->faker->company(),
            'source_id'    => $this->faker->uuid(),
            'title'        => $this->faker->sentence(),
            'content'      => $this->faker->paragraphs(3, true),
            'description'  => $this->faker->paragraph(),
            'author'       => $this->faker->name(),
            'category'     => $this->faker->word(),
            'url'          => $this->faker->url(),
            'image_url'    => $this->faker->imageUrl(),
            'published_at' => $this->faker->dateTimeBetween('-1 year'),
            'fetched_at'   => now(),
        ];
    }
}
