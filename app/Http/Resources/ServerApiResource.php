<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServerApiResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            "ip_address" => $this->ip_address,
            'country' => $this->country->name,
            'country_code' => $this->country->code,
            'ovpn' => $this->ovpn,
            'ispro' => (string)$this->ispro,
            'active_connection' => $this->active_connection,
            'state' => $this->state ?? "",
            'username' => $this->username ?? "",
            'password' => $this->password ?? "",
        ];
    }

}
