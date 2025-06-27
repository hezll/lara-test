<?php

namespace App\Console\Commands\Contact;

use Illuminate\Console\Command;
use App\Domain\Contact\Models\Contact;
use App\Domain\Contact\DTOs\ContactListData;
use App\Domain\Contact\Services\ContactService;
use Illuminate\Http\Request;

class ContactListCommand extends Command
{
    protected $signature = 'contact:list 
                            {--q= : Search term (name, email, or phone)} 
                            {--per-page=15 : Number of results per page}
                            {--page=1 : Page number to display}';

    protected $description = 'List contacts with optional search and pagination';

    public function __construct(protected ContactService $contactService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        // Create a fake request to reuse the ContactListData DTO
        $request = Request::create('/cli', 'GET', [
            'q' => $this->option('q'),
            'perPage' => $this->option('per-page'),
            'page' => $this->option('page'),
        ]);

        // Transform request into a DTO object
        $dto = ContactListData::fromRequest($request);

        // Fetch paginated contact list using existing service logic
        $contacts = $this->contactService->list($dto);

        // Handle empty result
        if ($contacts->isEmpty()) {
            $this->warn('No contacts found.');
            return 0;
        }

        // Display results in a table format
        $this->table(
            ['ID', 'Name', 'Email', 'Phone', 'Is Called','Last Contact', 'Created At'],
            $contacts->map(fn($c) => [
                $c->id,
                $c->name,
                $c->email,
                $c->phone,
                $c->is_called,
                $c->last_contacted_at,
                $c->created_at->toDateTimeString(),
            ])
        );

        return 0;
    }
}