<?php

namespace App\Console\Commands\Contact;

use Illuminate\Console\Command;
use App\Domain\Contact\Models\Contact;
use App\Domain\Contact\Services\ContactService;
use Illuminate\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Helper\Table;

class ContactShowCommand extends Command
{
   

    protected $signature = 'contact:show {id : Contact ID or UUID}';
    protected $description = 'Show a single contact by ID or UUID';

    public function __construct(protected ContactService $contactService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $id = $this->argument('id');

        try {
            $contact = $this->contactService->findById($id);

            $this->info('Contact Details:');
            $this->line('ID:      ' . $contact->id);
            $this->line('UUID:    ' . $contact->uuid);
            $this->line('Name:    ' . $contact->name);
            $this->line('Phone:   ' . $contact->phone);
            $this->line('Email:   ' . $contact->email);
            $this->line('Notes:   ' . $contact->notes);
            $this->line('Tags:    ' . implode(', ', $contact->tags ?? []));
            $this->line('Source:  ' . $contact->source);
            $this->line('Called:  ' . $contact->is_called);
            $this->line('LastContact:  '. $contact->last_contacted_at);
            $this->line('Created: ' . $contact->created_at);
            $this->line('Updated: ' . $contact->updated_at);

            return self::SUCCESS;
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            $this->error("Contact with ID {$id} not found.");
            return self::FAILURE;
        }
    }
    
}