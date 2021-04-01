<?php

namespace App\Services;

use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Attempt to log admin in.
     *
     * @param array $credentails
     * @throws ValidationException
     * @return string
     */
    public function logAdminIn(array $credentails): string
    {
        $token = auth('admin')->attempt($credentails);
        if (empty($token)) {
            throw ValidationException::withMessages([
                'username' => 'Invalid username',
                'password' => 'Invalid password',
            ]);
        }
        return $token;
    }
}
