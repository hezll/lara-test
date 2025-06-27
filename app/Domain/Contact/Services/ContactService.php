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

    public function search(array $filters): Collection
    {
        $query = Contact::query();

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (!empty($filters['phone'])) {
            $query->where('phone', 'like', '%' . $filters['phone'] . '%');
        }

        if (!empty($filters['email_domain'])) {
            $query->where('email', 'like', '%' . '@' . $filters['email_domain']);
        }

        return $query->get();
    }

    public function call(int|string $id): Contact
    {
        $contact = $this->findById($id);
        $contact->is_called = true;
        $contact->last_contacted_at = now();
        $contact->save();

        return $contact;
    }

    public function list(ContactListData $dto): LengthAwarePaginator
    {
        return Contact::query()
            ->orderByDesc('created_at')
            ->paginate(perPage: $dto->perPage, page: $dto->page);
    }
}
