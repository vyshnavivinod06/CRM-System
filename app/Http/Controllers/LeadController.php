<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeadRequest;
use App\Models\Lead;
use App\Services\ContactCreator;
use App\Services\ContactSources\LeadContactSource;
use Illuminate\Http\JsonResponse;

class LeadController extends Controller
{
    public function store(StoreLeadRequest $request, ContactCreator $contactCreator): JsonResponse
    {
        $lead = Lead::create($request->validated());
        $contact = $contactCreator->createFrom(new LeadContactSource($lead));

        return response()->json([
            'lead' => $lead->fresh('contact'),
            'contact' => $contact,
        ], 201);
    }
}
