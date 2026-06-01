<?php

namespace App\Services;

use App\Contracts\ContactSource;
use App\Models\Contact;
use Illuminate\Support\Facades\DB;

class ContactCreator
{
    public function createFrom(ContactSource $source): Contact
    {
        return DB::transaction(function () use ($source): Contact {
            $sourceModel = $source->sourceModel();

            $contact = Contact::create([
                ...$source->contactData()->toArray(),
                'source_type' => $sourceModel->getMorphClass(),
                'source_id' => $sourceModel->getKey(),
            ]);

            if ($sourceModel->isFillable('contact_id')) {
                $sourceModel->forceFill(['contact_id' => $contact->id])->save();
            }

            return $contact;
        });
    }
}
