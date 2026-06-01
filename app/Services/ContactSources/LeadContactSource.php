<?php

namespace App\Services\ContactSources;

use App\Contracts\ContactSource;
use App\Data\ContactData;
use App\Models\Lead;
use Illuminate\Database\Eloquent\Model;

final readonly class LeadContactSource implements ContactSource
{
    public function __construct(private Lead $lead) {}

    public function contactData(): ContactData
    {
        return new ContactData(
            firstName: $this->lead->first_name,
            lastName: $this->lead->last_name,
            email: $this->lead->email,
            phone: $this->lead->phone,
            business: filled($this->lead->company_name),
            companyName: $this->lead->company_name,
        );
    }

    public function sourceModel(): Model
    {
        return $this->lead;
    }
}
