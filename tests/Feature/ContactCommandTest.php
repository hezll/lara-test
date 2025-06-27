<?php

use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Testing\PendingCommand;
use App\Domain\Contact\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, Illuminate\Foundation\Testing\RefreshDatabase::class);

// Test contact:create CLI
it('can create a contact via CLI', function () {
    $this->artisan('contact:upsert', [
        '--name' => 'Alice CLI',
        '--email' => 'alice.cli@example.com',
        '--phone' => '+61411112222',
    ])
        ->expectsOutputToContain('Contact upserted: ID')
        ->assertExitCode(0);

    $this->assertDatabaseHas('contacts', [
        'email' => 'alice.cli@example.com',
        'name' => 'Alice CLI',
        'phone' => '+61411112222',
    ]);
});


// Test contact:list CLI
it('can list contacts via CLI', function () {
    Contact::factory()->create([
        'name' => 'Alice',
        'email' => 'alice@example.com',
        'phone' => '+61410000001',
    ]);

    Contact::factory()->create([
        'name' => 'Bob',
        'email' => 'bob@example.com',
        'phone' => '+61410000002',
    ]);

    Contact::makeAllSearchable();

    $this->artisan('contact:list', [
        '--per-page' => 10,
        '--page' => 1,
    ])
    ->expectsOutputToContain('Name')
    ->expectsOutputToContain('Alice')
    ->expectsOutputToContain('Bob')
    ->assertExitCode(0);
});

// Test contact:call CLI
it('can mark a contact as called via CLI', function () {
    $contact = Contact::factory()->create([
        'is_called' => false,
    ]);

    $this->artisan('contact:call', [
        'id' => $contact->id,
    ])
    ->expectsOutput("Contact ID {$contact->id} marked as called.")
    ->assertExitCode(0);

    $contact->refresh();
    expect($contact->is_called)->toBeTrue();
});
