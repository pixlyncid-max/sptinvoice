<?php

namespace Tests\Feature;

use App\Jobs\SendCampaignEmail;
use App\Mail\BroadcastMail;
use App\Models\EmailCampaign;
use App\Models\EmailCampaignRecipient;
use App\Models\EmailContact;
use App\Models\EmailLog;
use App\Models\EmailTemplate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class EmailMarketingTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $karyawan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->karyawan = User::factory()->create([
            'role' => 'karyawan',
        ]);
    }

    public function test_admin_can_access_email_marketing_contacts_index(): void
    {
        $response = $this->actingAs($this->admin)->get(route('email-marketing.contacts.index'));
        $response->assertStatus(200);
    }

    public function test_admin_can_render_template_create_and_edit_views(): void
    {
        $response = $this->actingAs($this->admin)->get(route('email-marketing.templates.create'));
        $response->assertStatus(200);
        $response->assertSee('Buat Template Email');

        $template = EmailTemplate::create([
            'name' => 'Sample Template',
            'subject' => 'Sample Subject',
            'body' => 'Hello {{name}}',
            'created_by' => $this->admin->id,
        ]);

        $editResponse = $this->actingAs($this->admin)->get(route('email-marketing.templates.edit', $template));
        $editResponse->assertStatus(200);
        $editResponse->assertSee('Edit Template Email');
    }

    public function test_admin_can_render_campaign_create_view(): void
    {
        $response = $this->actingAs($this->admin)->get(route('email-marketing.campaigns.create'));
        $response->assertStatus(200);
        $response->assertSee('Buat Campaign Email Baru');
    }

    public function test_karyawan_cannot_access_email_marketing(): void
    {
        $response = $this->actingAs($this->karyawan)->get(route('email-marketing.contacts.index'));
        $response->assertRedirect(route('gaa.index'));
    }

    public function test_admin_can_create_contact_and_duplicate_is_prevented(): void
    {
        $response = $this->actingAs($this->admin)->post(route('email-marketing.contacts.store'), [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'company' => 'PT Maju Terus',
            'is_subscribed' => 1,
        ]);

        $response->assertRedirect(route('email-marketing.contacts.index'));
        $this->assertDatabaseHas('email_contacts', [
            'email' => 'budi@example.com',
            'company' => 'PT Maju Terus',
            'is_subscribed' => 1,
        ]);

        // Attempt to create duplicate email
        $duplicateResponse = $this->actingAs($this->admin)->post(route('email-marketing.contacts.store'), [
            'name' => 'Budi Duplicate',
            'email' => 'budi@example.com',
            'company' => 'PT Lain',
        ]);

        $duplicateResponse->assertSessionHasErrors('email');
    }

    public function test_admin_can_create_and_duplicate_email_template(): void
    {
        $response = $this->actingAs($this->admin)->post(route('email-marketing.templates.store'), [
            'name' => 'Template Promo',
            'subject' => 'Penawaran Spesial {{company}}',
            'body' => 'Halo {{name}}, kami memiliki promo khusus untuk {{company}}.',
        ]);

        $response->assertRedirect(route('email-marketing.templates.index'));
        $template = EmailTemplate::where('name', 'Template Promo')->first();
        $this->assertNotNull($template);

        // Duplicate template
        $dupResponse = $this->actingAs($this->admin)->post(route('email-marketing.templates.duplicate', $template));
        $dupResponse->assertRedirect(route('email-marketing.templates.index'));

        $this->assertDatabaseHas('email_templates', [
            'name' => 'Template Promo (Salinan)',
        ]);
    }

    public function test_campaign_creates_recipients_and_dispatches_jobs(): void
    {
        Queue::fake();

        $contact1 = EmailContact::create([
            'name' => 'Alice',
            'email' => 'alice@example.com',
            'is_subscribed' => true,
        ]);

        $contact2 = EmailContact::create([
            'name' => 'Bob',
            'email' => 'bob@example.com',
            'is_subscribed' => true,
        ]);

        $unsubscribedContact = EmailContact::create([
            'name' => 'Charlie',
            'email' => 'charlie@example.com',
            'is_subscribed' => false,
        ]);

        $template = EmailTemplate::create([
            'name' => 'General Notice',
            'subject' => 'Important Notice',
            'body' => 'Hello {{name}}',
            'created_by' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->post(route('email-marketing.campaigns.store'), [
            'name' => 'Test Campaign 1',
            'subject' => 'Important Notice for {{name}}',
            'template_id' => $template->id,
            'recipient_type' => 'all_subscribed',
        ]);

        $campaign = EmailCampaign::where('name', 'Test Campaign 1')->first();
        $this->assertNotNull($campaign);
        $this->assertEquals(2, $campaign->total_recipients); // only 2 subscribed

        $response->assertRedirect(route('email-marketing.campaigns.show', $campaign));

        // Ensure SendCampaignEmail job was pushed for both recipients
        Queue::assertPushed(SendCampaignEmail::class, 2);
    }

    public function test_job_sends_email_individually_and_replaces_variables(): void
    {
        Mail::fake();

        $contact = EmailContact::create([
            'name' => 'Diana Prince',
            'email' => 'diana@example.com',
            'company' => 'Themyscira Corp',
            'is_subscribed' => true,
        ]);

        $template = EmailTemplate::create([
            'name' => 'Exclusive Promo',
            'subject' => 'Special for {{company}}',
            'body' => '<p>Halo {{name}} dari {{company}} ({{email}}), silakan unsubscribe di <a href="{{unsubscribe_url}}">sini</a>.</p>',
            'created_by' => $this->admin->id,
        ]);

        $campaign = EmailCampaign::create([
            'name' => 'Exclusive Campaign',
            'subject' => 'Special for {{company}}',
            'template_id' => $template->id,
            'status' => 'queued',
            'total_recipients' => 1,
            'sent_count' => 0,
            'failed_count' => 0,
            'created_by' => $this->admin->id,
        ]);

        $recipient = EmailCampaignRecipient::create([
            'campaign_id' => $campaign->id,
            'contact_id' => $contact->id,
            'email' => $contact->email,
            'status' => 'pending',
            'attempts' => 0,
        ]);

        // Execute Job synchronously
        $job = new SendCampaignEmail($recipient->id);
        $job->handle();

        // Verify email was sent to Diana individually
        Mail::assertSent(BroadcastMail::class, function ($mail) {
            return $mail->hasTo('diana@example.com') &&
                   $mail->emailSubject === 'Special for Themyscira Corp' &&
                   str_contains($mail->emailBody, 'Halo Diana Prince') &&
                   str_contains($mail->emailBody, 'Themyscira Corp');
        });

        // Verify recipient status updated to sent
        $recipient->refresh();
        $this->assertEquals('sent', $recipient->status);
        $this->assertNotNull($recipient->sent_at);

        // Verify Campaign stats updated
        $campaign->refresh();
        $this->assertEquals(1, $campaign->sent_count);
        $this->assertEquals('completed', $campaign->status);

        // Verify EmailLog created
        $this->assertDatabaseHas('email_logs', [
            'campaign_id' => $campaign->id,
            'recipient_email' => 'diana@example.com',
            'status' => 'sent',
        ]);
    }

    public function test_campaign_can_include_optional_attachment(): void
    {
        \Illuminate\Support\Facades\Storage::fake('local');
        Queue::fake();

        $contact = EmailContact::create([
            'name' => 'Budi',
            'email' => 'budi@example.com',
            'is_subscribed' => true,
        ]);

        $template = EmailTemplate::create([
            'name' => 'Template with attachment',
            'subject' => 'Subject',
            'body' => 'Body',
            'created_by' => $this->admin->id,
        ]);

        $file = \Illuminate\Http\UploadedFile::fake()->create('proposal.pdf', 500, 'application/pdf');

        $response = $this->actingAs($this->admin)->post(route('email-marketing.campaigns.store'), [
            'name' => 'Campaign with attachment',
            'subject' => 'Subject with attachment',
            'template_id' => $template->id,
            'recipient_type' => 'all_subscribed',
            'attachment' => $file,
        ]);

        $campaign = EmailCampaign::where('name', 'Campaign with attachment')->first();
        $this->assertNotNull($campaign);
        $this->assertEquals('proposal.pdf', $campaign->attachment_name);
        $this->assertNotNull($campaign->attachment_path);
        \Illuminate\Support\Facades\Storage::disk('local')->assertExists($campaign->attachment_path);
    }

    public function test_marketing_user_can_access_email_marketing_and_is_redirected_from_dashboard(): void
    {
        $marketing = User::factory()->create([
            'role' => 'marketing',
        ]);

        // Dashboard redirects to email marketing contacts
        $dashResponse = $this->actingAs($marketing)->get(route('dashboard'));
        $dashResponse->assertRedirect(route('email-marketing.contacts.index'));

        // Accessing contacts index succeeds
        $contactsResponse = $this->actingAs($marketing)->get(route('email-marketing.contacts.index'));
        $contactsResponse->assertStatus(200);

        // Accessing templates index succeeds
        $templatesResponse = $this->actingAs($marketing)->get(route('email-marketing.templates.index'));
        $templatesResponse->assertStatus(200);

        // Accessing campaigns index succeeds
        $campaignsResponse = $this->actingAs($marketing)->get(route('email-marketing.campaigns.index'));
        $campaignsResponse->assertStatus(200);

        // Accessing logs index succeeds
        $logsResponse = $this->actingAs($marketing)->get(route('email-marketing.logs.index'));
        $logsResponse->assertStatus(200);
    }

    public function test_marketing_user_cannot_access_non_email_marketing_routes(): void
    {
        $marketing = User::factory()->create([
            'role' => 'marketing',
        ]);

        // Invoices is blocked
        $invoiceResponse = $this->actingAs($marketing)->get(route('invoices.index'));
        $invoiceResponse->assertRedirect(route('email-marketing.contacts.index'));

        // Clients is blocked
        $clientResponse = $this->actingAs($marketing)->get(route('clients.index'));
        $clientResponse->assertRedirect(route('email-marketing.contacts.index'));

        // Salary is blocked
        $salaryResponse = $this->actingAs($marketing)->get(route('salary.index'));
        $salaryResponse->assertRedirect(route('email-marketing.contacts.index'));

        // Attendance is blocked
        $attendanceResponse = $this->actingAs($marketing)->get(route('attendance.index'));
        $attendanceResponse->assertRedirect(route('email-marketing.contacts.index'));

        // Users is blocked
        $usersResponse = $this->actingAs($marketing)->get(route('users.index'));
        $usersResponse->assertRedirect(route('email-marketing.contacts.index'));
    }
}
