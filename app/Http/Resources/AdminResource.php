<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Admin;

class AdminResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            'username' => $this->username,
            'role' => array_search($this->role, Admin::ROLES_FA),
            "social_token" => env("DOMAIN") . "?social=" . $this->social_token,
        ];
    }
}
