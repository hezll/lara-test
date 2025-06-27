<?php

namespace App\Domain\Contact\DTOs;

class ContactData
{
    public function __construct(
        public readonly ?string $uuid,
        public readonly string $name,
        public readonly string $phone,
        public readonly string $email,
        public readonly ?string $notes = null,
        public readonly ?array $tags = [],
        public readonly ?string $source = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            uuid: $data['uuid'] ?? null,
            name: $data['name'],
            phone: $data['phone'],
            email: $data['email'],
            notes: $data['notes'] ?? null,
            tags: $data['tags'] ?? [],
            source: $data['source'] ?? null,
        );
    }
}
