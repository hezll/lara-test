<?php

namespace App\Domain\Contact\DTOs;

use Illuminate\Http\Request;

class ContactListData
{
    public function __construct(
        public readonly int $page = 1,
        public readonly int $perPage = 15
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            page: max(1, (int) $request->get('page', 1)),
            perPage: min((int) $request->get('per_page', 15), 100)
        );
    }
}
