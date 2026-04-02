<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('company_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('password');
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_active')->default(true);
            // Per-client feature flags
            $table->boolean('feature_onboarding')->default(true);
            $table->boolean('feature_project_status')->default(true);
            $table->boolean('feature_website_review')->default(false);
            $table->boolean('feature_app_review')->default(false);
            $table->boolean('feature_support_tickets')->default(true);
            $table->boolean('feature_document_vault')->default(false);
            $table->boolean('feature_seo_reports')->default(false);
            $table->boolean('feature_ai_status')->default(false);
            $table->boolean('feature_hosting_details')->default(false);
            $table->boolean('feature_invoices')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
