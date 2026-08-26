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
        // 1. Email Contacts
        if (!Schema::hasTable('email_contacts')) {
            Schema::create('email_contacts', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('company')->nullable();
                $table->boolean('is_subscribed')->default(true);
                $table->string('unsubscribe_token', 64)->nullable()->unique();
                $table->timestamps();
            });
        }

        // 2. Email Templates
        if (!Schema::hasTable('email_templates')) {
            Schema::create('email_templates', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('subject')->nullable();
                $table->longText('body');
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }

        // 3. Email Campaigns
        if (!Schema::hasTable('email_campaigns')) {
            Schema::create('email_campaigns', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('subject');
                $table->foreignId('template_id')->nullable()->constrained('email_templates')->nullOnDelete();
                $table->enum('status', ['draft', 'queued', 'sending', 'completed', 'failed', 'paused'])->default('draft');
                $table->integer('total_recipients')->default(0);
                $table->integer('sent_count')->default(0);
                $table->integer('failed_count')->default(0);
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }

        // 4. Email Campaign Recipients
        if (!Schema::hasTable('email_campaign_recipients')) {
            Schema::create('email_campaign_recipients', function (Blueprint $table) {
                $table->id();
                $table->foreignId('campaign_id')->constrained('email_campaigns')->cascadeOnDelete();
                $table->foreignId('contact_id')->nullable()->constrained('email_contacts')->nullOnDelete();
                $table->string('email');
                $table->enum('status', ['pending', 'sent', 'failed', 'skipped'])->default('pending');
                $table->integer('attempts')->default(0);
                $table->timestamp('sent_at')->nullable();
                $table->text('error_message')->nullable();
                $table->timestamps();

                $table->index(['campaign_id', 'status']);
            });
        }

        // 5. Email Logs
        if (!Schema::hasTable('email_logs')) {
            Schema::create('email_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('campaign_id')->nullable()->constrained('email_campaigns')->nullOnDelete();
                $table->string('recipient_email');
                $table->string('subject');
                $table->enum('status', ['sent', 'failed']);
                $table->text('error_message')->nullable();
                $table->timestamp('sent_at')->useCurrent();
                $table->timestamps();

                $table->index('status');
                $table->index('recipient_email');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_logs');
        Schema::dropIfExists('email_campaign_recipients');
        Schema::dropIfExists('email_campaigns');
        Schema::dropIfExists('email_templates');
        Schema::dropIfExists('email_contacts');
    }
};
