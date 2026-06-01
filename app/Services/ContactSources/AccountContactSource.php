<?php

namespace App\Services\ContactSources;

use App\Contracts\ContactSource;
use App\Data\ContactData;
use App\Models\Account;
use Illuminate\Database\Eloquent\Model;

final readonly class AccountContactSource implements ContactSource
{
    public function __construct(private Account $account) {}

    public function contactData(): ContactData
    {
        return new ContactData(
            firstName: $this->account->first_name,
            lastName: $this->account->last_name,
            email: $this->account->email,
            phone: $this->account->phone,
            business: true,
            companyName: $this->account->name,
        );
    }

    public function sourceModel(): Model
    {
        return $this->account;
    }
}
