<?php

namespace Tests\Feature;

use App\Models\ConsultancyRequest;
use App\Models\ContactMessage;
use App\Models\ServiceCategory;
use Database\Seeders\RichContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** The two public forms are separate: general messages and consultancy requests. */
class FormSubmissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RichContentSeeder::class);
    }

    public function test_both_form_pages_render(): void
    {
        $this->get(route('contact'))->assertOk()->assertSee('Send a message');
        $this->get(route('consultancy.create'))->assertOk()->assertSee('Request consultancy', false);
    }

    public function test_contact_message_is_stored(): void
    {
        $this->post(route('contact.store'), [
            'name' => 'Test Person',
            'email' => 't@example.com',
            'subject' => 'Hello',
            'message' => 'This is a test enquiry message body.',
        ])->assertRedirect();

        $this->assertDatabaseHas(ContactMessage::class, [
            'email' => 't@example.com',
            'status' => 'new',
        ]);
    }

    public function test_contact_message_rejects_a_short_body(): void
    {
        $this->post(route('contact.store'), [
            'name' => 'Test Person',
            'email' => 't@example.com',
            'message' => 'too short',
        ])->assertSessionHasErrors('message');

        $this->assertSame(0, ContactMessage::count());
    }

    public function test_consultancy_request_is_stored_with_its_service(): void
    {
        $category = ServiceCategory::with('services')->first();
        $service = $category->services->first();

        $this->post(route('consultancy.store'), [
            'name' => 'Client X',
            'email' => 'c@example.com',
            'service_category_id' => $category->id,
            'area_of_interest' => $service->name,
            'requirement' => 'We need a structural assessment of two buildings before the monsoon.',
        ])->assertRedirect(route('consultancy.thanks'));

        $this->assertDatabaseHas(ConsultancyRequest::class, [
            'email' => 'c@example.com',
            'service_category_id' => $category->id,
            'area_of_interest' => $service->name,
        ]);
        $this->assertSame(0, ContactMessage::count());
    }
}
