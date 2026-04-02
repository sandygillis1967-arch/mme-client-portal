<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('app_name');
            $table->string('version')->nullable();
            $table->string('staging_url');
            $table->enum('status', ['in_review', 'approved'])->default('in_review');
            $table->text('approval_notes')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('app_review_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('app_review_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('screen_section');
            $table->enum('issue_type', ['bug', 'change_request', 'design_issue', 'works_great']);
            $table->text('description');
            $table->enum('status', ['open', 'in_progress', 'done'])->default('open');
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_review_feedback');
        Schema::dropIfExists('app_reviews');
    }
};
