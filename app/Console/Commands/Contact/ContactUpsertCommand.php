<?php

namespace App\Console\Commands\Contact;

use Illuminate\Console\Command;
use App\Domain\Contact\Models\Contact;
use App\Domain\Contact\Services\ContactService;
use App\Domain\Contact\DTOs\ContactData;
use App\Domain\Contact\Http\Requests\StoreContactRequest;
use Illuminate\Support\Facades\Validator;

class ContactUpsertCommand extends Command
{
    protected $signature = 'contact:upsert 
                            {--uuid= : Optional UUID}
                            {--name= : Contact name} 
                            {--email= : Email address} 
                            {--phone= : Phone number} 
                            {--notes= : Notes} 
                            {--tags= : JSON-encoded tag array} 
                            {--source= : Source (e.g., linkedin) }';

    protected $description = 'Upsert a contact via CLI using ContactService and validation rules';

    public function __construct(protected ContactService $contactService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        // Get raw input
        $data = [
            'uuid'   => $this->option('uuid'),
            'name'   => $this->option('name'),
            'email'  => $this->option('email'),
            'phone'  => $this->option('phone'),
            'notes'  => $this->option('notes'),
            'tags'   => $this->option('tags') ? json_decode($this->option('tags'), true) : [],
            'source' => $this->option('source'),
        ];

        // Use FormRequest rules
        $rules = (new StoreContactRequest)->rules();

        // Manually validate
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            $this->error('Validation failed:');
            foreach ($validator->errors()->all() as $message) {
                $this->line(" - $message");
            }
            return self::FAILURE;
        }

        // Create DTO
        $dto = ContactData::fromArray($data);

        // Call Service
        $contact = $this->contactService->upsert($dto);

        $this->info("Contact upserted: ID {$contact->id}, Name: {$contact->name}");
        return self::SUCCESS;
    }
}