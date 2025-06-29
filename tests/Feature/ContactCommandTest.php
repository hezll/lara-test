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

it('fails to create contact via CLI when name is missing', function () {
    $this->artisan('contact:upsert', [
        '--email' => 'cli-missing-name@example.com',
        '--phone' => '+61499999999',
    ])
    ->expectsOutputToContain('The name field is required.')
    ->assertExitCode(1);
});

it('fails to create contact via CLI when phone already exists', function () {
    Contact::factory()->create([
        'name' => 'Alice Existing',
        'email' => 'alice.existing@example.com',
        'phone' => '+61412345678',
    ]);

    $this->artisan('contact:upsert', [
        '--name' => 'Alice Dup',
        '--email' => 'alice.dup@example.com',
        '--phone' => '+61412345678',
    ])
    ->expectsOutputToContain('The phone has already been taken.')
    ->assertExitCode(1);
});


it('can safely re-call an already called contact', function () {
    $contact = Contact::factory()->create(['is_called' => true]);

    $this->artisan('contact:call', [
        'id' => $contact->id,
    ])
    ->expectsOutput("Contact ID {$contact->id} marked as called.") // 或提示已处理
    ->assertExitCode(0);
});
