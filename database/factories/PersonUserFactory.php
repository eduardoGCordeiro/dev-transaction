<?php

namespace Database\Factories;

use App\Models\PersonUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonUserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PersonUser::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => function () {
                return User::factory()->create()->id;
            },
            'cpf' => $this->faker->unique()->regexify('[0-9]{11}')
        ];
    }
}
