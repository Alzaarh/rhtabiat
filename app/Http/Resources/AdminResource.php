<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Admin;

class AdminResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'username' => $this->username,
            'role' => array_search($this->role, Admin::ROLES_FA),
        ];
    }
}
