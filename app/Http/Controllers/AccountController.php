<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAccountRequest;
use App\Models\Account;
use App\Services\ContactCreator;
use App\Services\ContactSources\AccountContactSource;
use Illuminate\Http\JsonResponse;

class AccountController extends Controller
{
    public function store(StoreAccountRequest $request, ContactCreator $contactCreator): JsonResponse
    {
        $account = Account::create($request->validated());
        $contact = $contactCreator->createFrom(new AccountContactSource($account));

        return response()->json([
            'account' => $account->fresh('contact'),
            'contact' => $contact,
        ], 201);
    }
}
