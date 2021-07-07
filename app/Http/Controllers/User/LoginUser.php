<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Jobs\NotifyViaSms;
use App\Models\VerificationCode;
use Illuminate\Http\Request;

class LoginUser extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        request()->validate(
            [
                'phone' => 'required|digits:11|exists:users',
                'email' => 'required_without:phone|email|exists:user_details',
                'password' => 'required_with:email',
            ]
        );

        $code = VerificationCode::create(['code' => rand(10000, 99999), ]);
//        NotifyViaSms::dispatchIf(request()->has('phone'), request()->input('phone'), config('app.sms_patterns.verification'), ['code' => ])

    }
}
