<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserRegisterAttempted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $phone;

    public $code;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($phone, $code)
    {
        $this->phone = $phone;
        $this->code = $code;
    }
}
