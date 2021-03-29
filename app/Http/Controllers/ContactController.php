<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveContactRequest;
use App\Http\Resources\ContactResource;
use App\Models\Contact;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:admin', 'role:admin'])->except('store');
    }

    public function index()
    {
        return ContactResource::collection(Contact::paginate());
    }

    public function show(Contact $contact)
    {
        return new ContactResource($contact);
    }

    public function store(SaveContactRequest $request)
    {
        Contact::create($request->validated());
        return jsonResponse(['message' => 'Submitted'], 201);
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return jsonResponse(['message' => 'Deleted']);
    }
}
