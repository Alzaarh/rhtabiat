<?php

namespace App\Listeners;

use App\Events\UserRegisterAttempted;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendVerificationCodeViaSMS implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  UserRegisterAttempted  $event
     * @return void
     */
    public function handle(UserRegisterAttempted $event)
    {
        sendSMS(
            $event->phone,
            config('app.sms_patterns.verification'),
            ['code' => $event->code],
        );
    }
}
