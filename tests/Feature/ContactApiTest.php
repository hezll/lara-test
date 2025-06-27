<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use App\Domain\Contact\Models\Contact;
use function Pest\Laravel\{get, post, put, delete};


uses(TestCase::class, RefreshDatabase::class);


it('can list contacts', function () {
    Contact::factory()->count(3)->create();

    get('/api/v1/contacts')
        ->assertOk()
        ->assertJson(fn (AssertableJson $json) =>
            $json->has('data')
                 ->has('meta')
                 ->has('links')
        );
});

it('can show a contact', function () {
    $contact = Contact::factory()->create();

    get("/api/v1/contacts/{$contact->id}")
        ->assertOk()
        ->assertJsonPath('data.id', $contact->id);
});

it('can create a new contact', function () {
    $data = [
        'name' => 'Alice Test',
        'email' => 'alice@example.com',
        'phone' => '+61412345678',
    ];

    post('/api/v1/contacts', $data)
        ->assertCreated()
        ->assertJsonPath('data.name', 'Alice Test');
});

it('can search contacts by name, email or phone', function () {
    // Matching contact
   Contact::factory()->create([
        'name' => 'Alice Wonderland',
        'email' => 'alice@wonderland.com',
        'phone' => '+61411111111',
    ]);

    // Non-matching contact
    Contact::factory()->create([
        'name' => 'Bob Smith',
        'email' => 'bob@example.com',
        'phone' => '+61499999999',
    ]);

    sleep(0.5); // Ensure search index is updated

    get('/api/v1/contacts?q=Alice')
    ->assertOk()
    ->assertJson(fn (AssertableJson $json) =>
        $json->has('data', 1)
             ->has('meta')
             ->has('links')
             ->where('data.0.name', 'Alice Wonderland')
             ->where('data.0.email', 'alice@wonderland.com')
       
    );

});
