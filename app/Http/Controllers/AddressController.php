<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveAddressRequest;
use App\Http\Resources\AddressResource;
use App\Models\Address;

class AddressController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    public function index()
    {
        return AddressResource::collection(auth()->user()->addresses);
    }

    public function show(Address $address)
    {
        return new AddressResource($address);
    }

    public function store(SaveAddressRequest $request)
    {
        return new AddressResource(
            auth()->user()->addresses()->save(
                new Address($request->validated())
            )
        );
    }

    public function update(SaveAddressRequest $request, Address $address)
    {
        $address->update($request->validated());
        return new AddressResource($address);
    }

    public function destroy(Address $address)
    {
        $address->orders()->exists() ? $address->delete() : $address->forceDelete();
        return jsonResponse(['message' => 'Deleted']);
    }
}
