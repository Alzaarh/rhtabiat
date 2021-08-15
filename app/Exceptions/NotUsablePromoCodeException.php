<?php

namespace App\Exceptions;

use Exception;

class NotUsablePromoCodeException extends Exception
{
    public function render()
    {
        return response()->json(['message' => $this->getMessage()]);
    }
}
