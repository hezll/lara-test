<?php

namespace App\Domain\Contact\Services;

use App\Domain\Contact\Contracts\ContactServiceInterface;
use App\Domain\Contact\DTOs\ContactData;
use App\Domain\Contact\Models\Contact;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ContactService
{
    public function upsert(ContactData $data): Contact
    {
        return Contact::updateOrCreate(
            ['email' => $data->email],
            ['name' => $data->name, 'phone' => $data->phone]
        );
    }

    public function find(int $id): ?Contact
    {
        return Contact::find($id);
    }

    public function delete(int $id): void
    {
        Contact::findOrFail($id)->delete();
    }

    public function call(int $id): array
    {
        $contact = Contact::findOrFail($id);

        return [
            'status' => 'success',
            'message' => "Calling {$contact->name} at {$contact->phone}...",
        ];
    }

    public function search(string $query): Collection
    {
        return Contact::search($query)->get();
    }

    public function searchPaginated(string $query, int $perPage = 10): LengthAwarePaginator
    {
        return Contact::search($query)->paginate($perPage);
    }
}
