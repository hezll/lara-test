<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domain\Contact\Models\Contact;
use App\Domain\Contact\Enums\ContactStatus;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        Contact::factory()->count(20)->create()->each(function ($contact) {
            $contact->update([
                'status' => collect(ContactStatus::cases())->random(),
                'is_called' => fake()->boolean(30),
                'tags' => ['demo', 'seed'],
                'notes' => fake()->optional()->sentence(),
                'source' => fake()->randomElement(['import', 'manual', 'api']),
                'last_contacted_at' => fake()->optional()->dateTimeBetween('-1 month'),
            ]);
        });
    }
}
