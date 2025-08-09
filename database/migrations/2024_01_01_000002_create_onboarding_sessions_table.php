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
        Schema::create('onboarding_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('token')->unique();
            $table->string('full_name');
            $table->string('email');
            $table->string('password_hash');
            $table->string('company_name');
            $table->string('subdomain');
            $table->json('company_metadata')->nullable();
            $table->json('billing_info')->nullable();
            $table->enum('current_step', ['account', 'password', 'company', 'billing', 'confirmation'])->default('account');
            $table->boolean('is_completed')->default(false);
            $table->timestamp('expires_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('onboarding_sessions');
    }
};



