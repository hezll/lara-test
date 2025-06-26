<?php

namespace Database\Factories;

use App\Domain\Contact\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Domain\Contact\Enums\ContactStatus;
use Illuminate\Support\Str;

/**
 * @extends Factory<Contact>
 */
class ContactFactory extends Factory
{
    protected $model = Contact::class;

    public function definition(): array
    {
        return [
            'uuid' => Str::uuid(),

            'name' => $this->faker->name(),
            'phone' => $this->faker->unique()->numerify('+614########'), // AU E.164
            'email' => $this->faker->unique()->safeEmail(),

            'status' => $this->faker->randomElement(ContactStatus::cases()),
            'is_called' => $this->faker->boolean(30),
            'is_active' => $this->faker->boolean(90),

            'notes' => $this->faker->optional()->sentence(),
            'tags' => ['seeded', 'test'],
            'source' => $this->faker->randomElement(['import', 'manual', 'api']),
            'last_contacted_at' => $this->faker->optional()->dateTimeBetween('-1 month', 'now'),

            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
