<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->index();

            $table->string('name')->index();
            $table->string('phone')->unique()->index();
            $table->string('email')->unique()->index();

            $table->unsignedTinyInteger('status')->default(0)->index(); // enum:int
            $table->boolean('is_called')->default(false);
            $table->boolean('is_active')->default(true);

            $table->text('notes')->nullable();
            $table->json('tags')->nullable();
            $table->string('source')->nullable();
            $table->timestamp('last_contacted_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
