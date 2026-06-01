<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Contact;
use App\Models\Lead;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateContactFromSourcesTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_a_business_contact_when_an_account_is_created(): void
    {
        $response = $this->postJson('/api/accounts', [
            'name' => 'Acme BV',
            'first_name' => 'Ada',
            'last_name' => 'Lovelace',
            'email' => 'ada@example.com',
            'phone' => '+31000000000',
        ]);

        $response->assertCreated()
            ->assertJsonPath('contact.first_name', 'Ada')
            ->assertJsonPath('contact.business', true)
            ->assertJsonPath('contact.company_name', 'Acme BV');

        $account = Account::firstOrFail();
        $contact = Contact::firstOrFail();

        $this->assertTrue($account->contact->is($contact));
        $this->assertSame(Account::class, $contact->source_type);
        $this->assertSame($account->id, $contact->source_id);
    }

    public function test_it_creates_a_contact_when_a_lead_is_created(): void
    {
        $response = $this->postJson('/api/leads', [
            'first_name' => 'Grace',
            'last_name' => 'Hopper',
            'email' => 'grace@example.com',
            'company_name' => 'Compiler Labs',
        ]);

        $response->assertCreated()
            ->assertJsonPath('contact.first_name', 'Grace')
            ->assertJsonPath('contact.business', true)
            ->assertJsonPath('contact.company_name', 'Compiler Labs');

        $lead = Lead::firstOrFail();
        $contact = Contact::firstOrFail();

        $this->assertTrue($lead->contact->is($contact));
        $this->assertSame(Lead::class, $contact->source_type);
        $this->assertSame($lead->id, $contact->source_id);
    }

    public function test_it_validates_source_payloads_before_creating_contacts(): void
    {
        $response = $this->postJson('/api/accounts', [
            'name' => '',
            'email' => 'not-an-email',
        ]);

        $response->assertUnprocessable();

        $this->assertArrayHasKey('name', $response->json('errors'));
        $this->assertArrayHasKey('email', $response->json('errors'));

        $this->assertDatabaseCount('accounts', 0);
        $this->assertDatabaseCount('contacts', 0);
    }
}
