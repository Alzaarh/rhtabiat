<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Jobs\NotifyViaSms;

class NotifyUserForOrder extends Controller
{
    public function __invoke()
    {
        request()->validate(['phone' => 'required_without:address_id']);

        NotifyViaSms::dispatch(
            request()->phone,
            config('app.sms_patterns.order_created'),
            ['keyword' => 'اعتماد']
        );
        return response(null);
    }
}
