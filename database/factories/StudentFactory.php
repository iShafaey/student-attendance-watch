<?php

namespace Database\Factories;

use App\Models\StudentClass;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $class = StudentClass::inRandomOrder()->first();
        $firstName = $this->faker->firstName();
        $lastName = $this->faker->lastName();

        return [
            'student_code' => $this->faker->unique()->randomNumber(6),
            'class' => $class->id,
            'student_name' => $firstName,
            'father_name' => $lastName,
            'join_date' => $this->faker->date(),
            'phone_number' => $this->faker->phoneNumber(),
            'status' => 'active',
            'country_code' => '+20',
            'age' => $this->faker->numberBetween(12, 19),
            'fees' => $this->faker->randomFloat(2, 10, 600),
        ];
    }
}
