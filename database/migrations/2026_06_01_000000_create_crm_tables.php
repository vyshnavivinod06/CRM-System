<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table): void {
            $table->id();
            $table->string('first_name', 120)->nullable();
            $table->string('last_name', 120)->nullable();
            $table->string('email', 120)->nullable();
            $table->string('phone', 30)->nullable();
            $table->boolean('business')->default(false);
            $table->string('company_name', 120)->nullable();
            $table->string('source_type');
            $table->unsignedBigInteger('source_id');
            $table->timestamps();

            $table->index(['source_type', 'source_id']);
            $table->index('email');
        });

        Schema::create('accounts', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 120);
            $table->string('first_name', 120)->nullable();
            $table->string('last_name', 120)->nullable();
            $table->string('email', 120)->nullable();
            $table->string('phone', 30)->nullable();
            $table->foreignId('contact_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('leads', function (Blueprint $table): void {
            $table->id();
            $table->string('first_name', 120)->nullable();
            $table->string('last_name', 120)->nullable();
            $table->string('email', 120)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('company_name', 120)->nullable();
            $table->string('status', 60)->default('new');
            $table->foreignId('contact_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
        Schema::dropIfExists('accounts');
        Schema::dropIfExists('contacts');
    }
};
