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
            $json->has('data', 3)
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

// it('can search contacts', function () {
//     Contact::factory()->create(['name' => 'John Smith']);
//     Contact::factory()->create(['name' => 'Jane Doe']);

//     get('/api/v1/contacts/search?q=john')
//         ->assertOk()
//         ->assertJson(fn (AssertableJson $json) =>
//             $json->has('data', 1)
//                  ->where('data.0.name', 'John Smith')
//         );
// });

// it('can mark contact as called', function () {
//     $contact = Contact::factory()->create(['is_called' => false]);

//     post("/api/v1/contacts/{$contact->id}/call")
//         ->assertOk()
//         ->assertJsonPath('data.is_called', true);
// });

// it('can update existing contact (upsert)', function () {
//     $contact = Contact::factory()->create();

//     $data = [
//         'id' => $contact->id,
//         'name' => 'Updated Name',
//         'email' => 'updated@example.com',
//         'phone' => $contact->phone,
//     ];

//     post('/api/v1/contacts', $data)
//         ->assertOk()
//         ->assertJsonPath('data.name', 'Updated Name');
// });

// it('can delete contact', function () {
//     $contact = Contact::factory()->create();

//     delete("/api/v1/contacts/{$contact->id}")
//         ->assertNoContent();

//     $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
// });



