<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Jobs\NotifyViaSms;
use App\Models\Admin;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Services\CalcOrderDeliveryCostService;
use App\Models\Address;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Services\InitiateWithZarinpalService;
use App\Models\PromoCode;
use App\Rules\AvailablePromoCode;
use App\Rules\CanUsePromoCode;
use App\Rules\CheckMinPromoCode;
use App\Rules\ValidPromoCode;

class OrderController extends Controller {
	public function index() {
		if (request()->user()->role === Admin::ROLES['writer']) {
			abort(403);
		}
		return OrderResource::collection(Order::latest()->paginate(request()->query('count', 10)));
	}

	public function show(Order $order) {
		$order->load('items.product');
		return new OrderResource($order);
	}

	public function update(Order $order) {
		if ($order->status === Order::STATUS['being_processed']) {
			request()->validate(['delivery_code' => 'required']);
			$order->status = Order::STATUS['in_post_office'];
			$order->delivery_code = request()->delivery_code;
			$order->save();
			if ($order->forGuest()) {
				$phone = $order->guestDetail->mobile;
				$name = $order->guestDetail->name;
			}
			NotifyViaSms::dispatchSync(
				$phone,
				config('app.sms_patterns.order_post_office'),
				['name' => $name, 'code' => request()->delivery_code]
			);
		} elseif ($order->status === Order::STATUS['in_post_office']) {
			$order->status = Order::STATUS['delivered'];
			$order->save();
		}

		return response()->json(['message' => 'سفارش با موفقیت به روزرسانی شد']);
	}

	public function reject(Order $order) {
		$order->status = Order::STATUS['rejected'];
		$order->save();
		return response()->json(['message' => 'سفارش با موفقیت به رد شد']);
	}

	public function store(Request $request, CalcOrderDeliveryCostService $calcDeliveryCost, InitiateWithZarinpalService $paymentInit)
	{
		$address = Address::find($request->input("address_id"));
		$orderItems = auth("user")->user()->cart->products->load("product");
		$products = Product::find(
			$orderItems->map(fn ($item) => $item->product->id)->toArray()
		);
		$itemsPrice = 0;
		$itemsWeight = 0;
		$deliveryCost = 0;
		$packagePrice = 0;
		$attachedItems = [];
		$userEmail = auth("user")->user()->detail
			? auth("user")->user()->detail->email
			: "";
		$userPhone = auth("user")->user()->phone;

		$itemsPrice = $orderItems->reduce(
			fn ($carry, $item)
				=> ((100 - $item->product->off) * $item->price / 100) *
					$item->pivot->quantity +
					$carry,
					0
			);

		$request->validate([
			"address_id" => [
				"required",
				"exists:addresses,id",
			],
		]);



		foreach ($orderItems as $orderItem) {
			if ($orderItem->pivot->quantity > $orderItem->quantity) {
				throw ValidationException::withMessages([
					"quantity" => "تعداد محصول واردشده صحیح نمی باشد",
				]);
			}
		}

		$itemsPrice = $orderItems->reduce(
			fn ($carry, $item)
				=> ((100 - $item->product->off) * $item->price / 100) *
					$item->pivot->quantity +
					$carry,
					0
			);

		$itemsWeight = $orderItems->reduce(
			fn ($carry, $item) => $item->weight * $item->pivot->quantity + $carry,
			0
		);

		$deliveryCost = $calcDeliveryCost->handle(
			$itemsPrice,
			$address->province_id,
			$itemsWeight
		);

		$packagePrice = $products->reduce(
			fn ($carry, $product) => $carry + $product->package_price,
			0
		);

		foreach ($orderItems as $item) {
			$attachedItems[$item->id] = [
				"product_id" => $item->product->id,
				"price" => $item->price,
				"off" => $item->product->off,
				"quantity" => $item->pivot->quantity,
				"weight" => $item->weight,
			];
		}

		DB::beginTransaction();
		try {
			if ($result->input('promoCode')) {
				$promoCode = PromoCode::whereCode($result->input('promoCode'))->first();
				$promoCode->orders()->create([
					"delivery_cost" => $deliveryCost,
					"package_price" => $packagePrice,
					"address_id" => $address->id,
					"referer_id" => Admin::where("social_token", $request->input("social_token"))->value("id") ?? 0,
				])
			} else {
				$order = Order::create([
					"delivery_cost" => $deliveryCost,
					"package_price" => $packagePrice,
					"address_id" => $address->id,
					"referer_id" => Admin::where("social_token", $request->input("social_token"))->value("id"),
				]);
			}
			$order->items()->attach($attachedItems);
			DB::commit();
		} catch (\Exception $e) {
			DB::rollback();
		}

		$result = $paymentInit->handle($order->price, $userEmail, $userPhone);
		if (empty($result['errors']) && $result['data']['code'] == 100) {
			$order->transactions()->create([
					'amount' => $order->price,
					'authority' => $result['data']['authority'],
			]);
		}
		return response()->json([
			'message' => __('messages.order.store'),
			'data' => [
					'redirect_url' => config('app.zarinpal.redirect_url') . $result['data']['authority'],
			],
		], 201);
	}

	public function getUserOrders(Request $request)
	{
		$query = Order::latest();
		$query->whereHas('address', function ($query) {
			$query->where('user_id', request()->user()->id);
		});
		if ($request->has("status")) {
			$query->whereStatus($request->query("status"));
		}
		return OrderResource::collection($query->paginate(10));
	}

	public function getUserOrder(Order $order)
	{
		$order->load('items.product', "address");
		return new OrderResource($order);
	}

	public function reorder(Order $order, CalcOrderDeliveryCostService $calcDeliveryCost, InitiateWithZarinpalService $paymentInit)
	{
		$userEmail = auth("user")->user()->detail ? auth("user")->user()->detail->email : "";
		$userPhone = auth("user")->user()->phone;

		if ($order->status === Order::STATUS["not_paid"]) {
			$result = $paymentInit->handle($order->price, $userEmail, $userPhone);
			if (empty($result['errors']) && $result['data']['code'] == 100) {
				$order->transactions()->create([
					'amount' => $order->price,
					'authority' => $result['data']['authority'],
				]);
			}
			return response()->json([
				'message' => __('messages.order.store'),
				'data' => [
						'redirect_url' => config('app.zarinpal.redirect_url') . $result['data']['authority'],
				],
			], 201);
		} else {
			$orderItems = $order->items;
			$products = Product::find(
				$orderItems->map(fn ($item) => $item->product->id)->toArray()
			);
		$itemsPrice = 0;
		$itemsWeight = 0;
		$deliveryCost = 0;
		$packagePrice = 0;
		$attachedItems = [];

		$itemsPrice = $orderItems->reduce(
			fn ($carry, $item)
				=> ((100 - $item->product->off) * $item->price / 100) *
					$item->pivot->quantity +
					$carry,
					0
			);

		foreach ($orderItems as $orderItem) {
			if ($orderItem->pivot->quantity > $orderItem->quantity) {
				throw ValidationException::withMessages([
					"quantity" => "تعداد محصول واردشده صحیح نمی باشد",
				]);
			}
		}

		$itemsPrice = $orderItems->reduce(
			fn ($carry, $item)
				=> ((100 - $item->product->off) * $item->price / 100) *
					$item->pivot->quantity +
					$carry,
					0
			);

		$itemsWeight = $orderItems->reduce(
			fn ($carry, $item) => $item->weight * $item->pivot->quantity + $carry,
			0
		);

		$deliveryCost = $calcDeliveryCost->handle(
			$itemsPrice,
			$order->address->province_id,
			$itemsWeight
		);

		$packagePrice = $products->reduce(
			fn ($carry, $product) => $carry + $product->package_price,
			0
		);

		foreach ($orderItems as $item) {
			$attachedItems[$item->id] = [
				"product_id" => $item->product->id,
				"price" => $item->price,
				"off" => $item->product->off,
				"quantity" => $item->pivot->quantity,
				"weight" => $item->weight,
			];
		}

		DB::beginTransaction();
		try {
			$order = Order::create([
				"delivery_cost" => $deliveryCost,
				"package_price" => $packagePrice,
				"address_id" => $order->address->id,
			]);
			$order->items()->attach($attachedItems);
			DB::commit();
		} catch (\Exception $e) {
			DB::rollback();
		}

		$result = $paymentInit->handle($order->price, $userEmail, $userPhone);
		if (empty($result['errors']) && $result['data']['code'] == 100) {
			$order->transactions()->create([
					'amount' => $order->price,
					'authority' => $result['data']['authority'],
			]);
		}
		return response()->json([
			'message' => __('messages.order.store'),
			'data' => [
					'redirect_url' => config('app.zarinpal.redirect_url') . $result['data']['authority'],
			],
		], 201);
		}
	}
}
