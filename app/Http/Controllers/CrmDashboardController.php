<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAccountRequest;
use App\Http\Requests\StoreLeadRequest;
use App\Models\Account;
use App\Models\Contact;
use App\Models\Lead;
use App\Services\ContactCreator;
use App\Services\ContactSources\AccountContactSource;
use App\Services\ContactSources\LeadContactSource;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CrmDashboardController extends Controller
{
    public function index(): View
    {
        return view('crm.index', [
            'contacts' => Contact::latest()->get(),
            'accounts' => Account::with('contact')->latest()->get(),
            'leads' => Lead::with('contact')->latest()->get(),
        ]);
    }

    public function storeAccount(StoreAccountRequest $request, ContactCreator $contactCreator): RedirectResponse
    {
        $account = Account::create($request->validated());
        $contactCreator->createFrom(new AccountContactSource($account));

        return redirect('/crm')->with('status', 'Account and contact created.');
    }

    public function storeLead(StoreLeadRequest $request, ContactCreator $contactCreator): RedirectResponse
    {
        $lead = Lead::create($request->validated());
        $contactCreator->createFrom(new LeadContactSource($lead));

        return redirect('/crm')->with('status', 'Lead and contact created.');
    }
}
