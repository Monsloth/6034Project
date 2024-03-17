<?php

namespace Database\Factories;

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

class TravelIdeaFactory extends Factory
{
    protected $model = \App\Models\TravelIdea::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'destination' => $this->faker->city,
            'start_date' => $this->faker->date,
            'end_date' => $this->faker->date,
            'tags' => join(',', $this->faker->words),
            'user_name' => $this->faker->name
        ];
    }
}
