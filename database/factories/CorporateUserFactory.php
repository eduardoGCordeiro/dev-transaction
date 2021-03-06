<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\CorporateUser;
use Illuminate\Database\Eloquent\Factories\Factory;

class CorporateUserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CorporateUser::class;

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
            'cnpj' => $this->faker->unique()->regexify('[0-9]{14}')
        ];
    }
}
