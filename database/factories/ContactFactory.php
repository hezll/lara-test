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

            'name' =>  fake()->name(),
            'phone' =>  fake()->unique()->numerify('+614########'), // AU E.164
            'email' =>  fake()->unique()->safeEmail(),

            'status' => fake()->randomElement(ContactStatus::cases()),
            'is_called' =>  fake()->boolean(30),
            'is_active' =>  fake()->boolean(90),

            'notes' =>  fake()->optional()->sentence(),
            'tags' => ['seeded', 'test'],
            'source' =>  fake()->randomElement(['import', 'manual', 'api']),
            'last_contacted_at' =>  fake()->optional()->dateTimeBetween('-1 month', 'now'),

            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
