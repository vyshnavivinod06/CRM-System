<?php

namespace Tests\Unit;

use App\Contracts\ContactSource;
use App\Data\ContactData;
use App\Models\Contact;
use App\Models\Lead;
use App\Services\ContactCreator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactCreatorTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_accepts_any_future_source_that_implements_the_contract(): void
    {
        $sourceModel = Lead::create([
            'first_name' => 'Future',
            'last_name' => 'Source',
            'email' => 'future@example.com',
        ]);

        $source = new class($sourceModel) implements ContactSource
        {
            public function __construct(private readonly Lead $lead) {}

            public function contactData(): ContactData
            {
                return new ContactData(
                    firstName: 'Future',
                    lastName: 'Source',
                    email: 'future@example.com',
                    phone: null,
                );
            }

            public function sourceModel(): Model
            {
                return $this->lead;
            }
        };

        $contact = app(ContactCreator::class)->createFrom($source);

        $this->assertInstanceOf(Contact::class, $contact);
        $this->assertDatabaseHas('contacts', [
            'first_name' => 'Future',
            'last_name' => 'Source',
            'email' => 'future@example.com',
            'source_type' => Lead::class,
            'source_id' => $sourceModel->id,
        ]);
    }
}
