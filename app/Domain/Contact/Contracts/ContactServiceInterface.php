<?php

namespace App\Domain\Contact\Contracts;

use App\Domain\Contact\DTOs\ContactData;
use App\Domain\Contact\DTOs\ContactListData;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Domain\Contact\Models\Contact;
use Illuminate\Support\Collection;

interface ContactServiceInterface
{
    public function upsert(ContactData $data): Contact;

    public function findById(int|string $id): Contact;

    public function delete(int|string $id): void;

    public function call(int|string $id): Contact;

    public function list(ContactListData $dto): LengthAwarePaginator;
}
