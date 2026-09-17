<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('short_urls', function (Blueprint $table) {
            $table->id();

            // The company this short url belongs to (always set — SuperAdmin
            // cannot create short urls, so this is never created without a company).
            $table->foreignId('company_id')
                ->constrained()
                ->cascadeOnDelete();

            // The user who created the short url.
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->text('original_url');
            $table->string('short_code')->unique();

            // Spec calls for created_at only (no updated_at) — short urls
            // are treated as immutable once created.
            $table->timestamp('created_at')->nullable();
        });

        // company_id already gets an index from the foreign key constraint above.
        // short_code already gets a (unique) index from ->unique() above.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('short_urls');
    }
};
