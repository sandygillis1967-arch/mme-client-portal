<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('photo_uploads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('file_path');
            $table->string('original_name');
            $table->text('placement_description')->nullable();
            $table->text('text_to_add')->nullable();
            $table->enum('status', ['new', 'in_progress', 'complete'])->default('new');
            $table->timestamps();
        });

        Schema::create('page_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('page_title');
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['new', 'in_progress', 'complete'])->default('new');
            $table->timestamps();
        });

        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('service_name');
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['new', 'in_progress', 'complete'])->default('new');
            $table->timestamps();
        });

        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('subject');
            $table->text('description');
            $table->enum('priority', ['low', 'medium', 'urgent'])->default('medium');
            $table->enum('status', ['open', 'in_progress', 'closed'])->default('open');
            $table->text('admin_response')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_tickets');
        Schema::dropIfExists('service_requests');
        Schema::dropIfExists('page_requests');
        Schema::dropIfExists('photo_uploads');
    }
};
