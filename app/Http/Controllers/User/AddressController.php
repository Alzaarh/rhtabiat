<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveAddressRequest;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller {
	public function index() {
		return AddressResource::collection(request()->user()->addresses);
	}

	public function store(SaveAddressRequest $request) {
		return new AddressResource(
			request()
				->user()
				->addresses()
				->save(new Address($request->validated()))
		);
	}

	public function update(SaveAddressRequest $request, Address $address) {
		$address->update($request->validated());
		return response()->json(new AddressResource($address));
	}

	public function destroy(Address $address) {
		$address->delete();
		return response()->json(new AddressResource($address));
	}
}
