<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_platforms', function (Blueprint $table): void {
            $table->ulid();
            $table->foreignUlid(column: 'user_id')->constrained(table: 'users');

            $table->string(column: 'platform');
            $table->string(column: 'username');
            $table->json(column: 'stats');

            $table->boolean(column: 'verified')->default(value: false);
            $table->timestamp(column: 'last_updated_on');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_platforms');
    }
};
