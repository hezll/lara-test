<?php

namespace App\Console\Commands\Contact;

use Illuminate\Console\Command;
use App\Domain\Contact\Models\Contact;

class ContactDeleteCommand extends Command
{
    protected $signature = 'contact:delete {id}';

    protected $description = 'Delete a contact';

    public function handle(): int
    {
        $contact = Contact::find($this->argument('id'));

        if (!$contact) {
            $this->error('Contact not found.');
            return 1;
        }

        $contact->delete();
        $this->info('Contact deleted.');
        return 0;
    }
}