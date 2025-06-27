<?php

namespace App\Domain\Contact\Services;

use App\Domain\Contact\Contracts\ContactServiceInterface;
use App\Domain\Contact\DTOs\ContactData;
use App\Domain\Contact\DTOs\ContactListData;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Domain\Contact\Models\Contact;
use Illuminate\Support\Collection;

class ContactService implements ContactServiceInterface
{
    public function upsert(ContactData $data): Contact
    {
        return Contact::updateOrCreate(
            ['uuid' => $data->uuid],
            [
                'name' => $data->name,
                'phone' => $data->phone,
                'email' => $data->email,
                'notes' => $data->notes,
                'tags' => $data->tags,
                'source' => $data->source,
            ]
        );
    }

    public function findById(int|string $id): Contact
    {
        return Contact::findOrFail($id);
    }

    public function delete(int|string $id): void
    {
        $this->findById($id)->delete();
    }

  
    public function call(int|string $id): Contact
    {
        $contact = $this->findById($id);
        $contact->is_called = true;
        $contact->last_contacted_at = now();
        $contact->save();

        return $contact;
    }

    /**
     * Search and filter contacts using Meilisearch (via Laravel Scout).
     *
     * @param  ContactListData  $dto
     * @return LengthAwarePaginator
     */
    public function list(ContactListData $dto): LengthAwarePaginator
    {
        // Full-text search by name, phone, or email
        $query = Contact::search($dto->q ?? '');

        // Optional filters (Meilisearch filterable attributes must be configured)
        if ($dto->tag) {
            $query->where('tags', $dto->tag);
        }

        if ($dto->createdAfter) {
            $query->where('created_at', '>=', $dto->createdAfter);
        }

        // Optional sorting (sortable attributes must be configured in Meilisearch)
        if ($dto->sort) {
            $query->orderBy($dto->sort, $dto->direction ?? 'asc');
        }

        // Paginate results
        return $query->paginate(
            perPage: $dto->perPage ?? 15,
            page: $dto->page ?? 1
        );
    }
}
