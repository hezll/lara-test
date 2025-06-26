<?php

namespace App\Domain\Contact\Enums;

enum ContactStatus: int
{
    case New = 0;
    case Pending = 1;
    case Contacted = 2;
    case Closed = 3;

    public function label(): string
    {
        return match ($this) {
            self::New => 'New',
            self::Pending => 'Pending',
            self::Contacted => 'Contacted',
            self::Closed => 'Closed',
        };
    }
}
