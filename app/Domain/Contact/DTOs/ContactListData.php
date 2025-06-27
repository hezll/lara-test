<?php

namespace App\Domain\Contact\DTOs;

use Illuminate\Http\Request;

class ContactListData
{
    public function __construct(
        public ?string $q = null,
        public ?string $tag = null,
        public ?string $createdAfter = null,
        public ?string $sort = null,
        public ?string $direction = 'asc',
        public ?int $perPage = 15,
        public ?int $page = 1,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            q: $request->input('q'),
            tag: $request->input('tag'),
            createdAfter: $request->input('created_after'),
            sort: $request->input('sort'),
            direction: $request->input('direction', 'asc'),
            perPage: $request->integer('per_page', 15),
            page: $request->integer('page', 1),
        );
    }
}