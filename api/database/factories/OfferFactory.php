<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OfferFactory extends Factory
{
    protected $model = \App\Models\Offer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->sentence(3),
            'description'=> $this->faker->unique()->sentence(6),
            'remote'=>$this->faker->boolean(),
            'salary'=>$this->faker->randomFloat(0, 1000, 30000),
            'country_id'=>1
        ];
    }
}
