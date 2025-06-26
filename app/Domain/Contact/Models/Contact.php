<?php

namespace App\Domain\Contact\Models;

use App\Domain\Contact\Enums\ContactStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Database\Factories\ContactFactory;

class Contact extends Model
{
    use HasFactory, SoftDeletes;
    /**
     * The factory for the model.
     */
    protected static function newFactory(): ContactFactory
    {
        return ContactFactory::new();
    }

    protected $fillable = [
        'uuid',
        'name',
        'phone',
        'email',
        'status',
        'is_called',
        'is_active',
        'notes',
        'tags',
        'source',
        'last_contacted_at',
    ];

    protected $casts = [
        'status' => ContactStatus::class,
        'is_called' => 'boolean',
        'is_active' => 'boolean',
        'tags' => 'array',
        'last_contacted_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function ($contact) {
            $contact->uuid = Str::uuid();
        });
    }
}
