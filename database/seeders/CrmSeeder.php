<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Lead;
use App\Services\ContactCreator;
use App\Services\ContactSources\AccountContactSource;
use App\Services\ContactSources\LeadContactSource;
use Illuminate\Database\Seeder;

class CrmSeeder extends Seeder
{
    public function run(): void
    {
        $contactCreator = app(ContactCreator::class);

        $accounts = [
            [
                'name' => 'Acme BV',
                'first_name' => 'Ada',
                'last_name' => 'Lovelace',
                'email' => 'ada@example.com',
                'phone' => '+31000000001',
            ],
            [
                'name' => 'Northwind Traders',
                'first_name' => 'Alan',
                'last_name' => 'Turing',
                'email' => 'alan@example.com',
                'phone' => '+31000000002',
            ],
            [
                'name' => 'Compiler Labs',
                'first_name' => 'Grace',
                'last_name' => 'Hopper',
                'email' => 'grace@example.com',
                'phone' => '+31000000003',
            ],
        ];

        foreach ($accounts as $accountData) {
            $account = Account::firstOrCreate(
                ['email' => $accountData['email']],
                $accountData,
            );

            if ($account->contact_id === null) {
                $contactCreator->createFrom(new AccountContactSource($account));
            }
        }

        $leads = [
            [
                'first_name' => 'Katherine',
                'last_name' => 'Johnson',
                'email' => 'katherine@example.com',
                'phone' => '+31000000004',
                'company_name' => 'Orbital Analytics',
                'status' => 'new',
            ],
            [
                'first_name' => 'Margaret',
                'last_name' => 'Hamilton',
                'email' => 'margaret@example.com',
                'phone' => '+31000000005',
                'company_name' => 'Apollo Software',
                'status' => 'qualified',
            ],
            [
                'first_name' => 'Barbara',
                'last_name' => 'Liskov',
                'email' => 'barbara@example.com',
                'phone' => '+31000000006',
                'company_name' => null,
                'status' => 'new',
            ],
        ];

        foreach ($leads as $leadData) {
            $lead = Lead::firstOrCreate(
                ['email' => $leadData['email']],
                $leadData,
            );

            if ($lead->contact_id === null) {
                $contactCreator->createFrom(new LeadContactSource($lead));
            }
        }
    }
}
