<?php

namespace Database\Factories;

use App\Models\News;
use Illuminate\Database\Eloquent\Factories\Factory;

class NewsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = News::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence($nbWords = 6, $variableNbWords = true),
            'content' => $this->faker->paragraph(mt_rand(6, 10)),
            'image'=>$this->faker->imageUrl($width = 640, $height = 480),
            'slug' => $this->faker->slug,
            'created_at'=>now(),
            'by_user'=> $this->faker->unique()->userName
        ];
    }
}
