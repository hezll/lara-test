<?php

namespace App\Console\Commands\Contact;

use Illuminate\Console\Command;
use App\Domain\Contact\Models\Contact;
use App\Domain\Contact\Services\ContactService;


class ContactCallCommand extends Command
{
    protected $signature = 'contact:call 
                            {id : The ID of the contact to mark as called}';

    protected $description = 'Mark a contact as called by ID';

    public function __construct(protected ContactService $contactService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $id = $this->argument('id');

        // Check if contact exists
        $contact = Contact::find($id);

        if (! $contact) {
            $this->error("Contact with ID {$id} not found.");
            return 1;
        }

        try {
            // Call the service layer method to mark as called
            $this->contactService->call($id);

            $this->info("Contact ID {$id} marked as called.");
            return 0;
        } catch (\Throwable $e) {
            $this->error("Error: {$e->getMessage()}");
            return 1;
        }
    }
}