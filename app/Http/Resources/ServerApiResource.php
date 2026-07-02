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
            'name' => $this->country->name . ($this->state ? ' (' . $this->state . ')' : ' #' . $this->id),
            "ip_address" => $this->ip_address,
            'host' => $this->ip_address,
            'country' => $this->country->name,
            'country_code' => $this->country->code,
            'flag_emoji' => $this->country->flag ?? '🌐',
            'ovpn' => $this->ovpn,
            'ispro' => (string)$this->ispro,
            'active_connection' => $this->active_connection,
            'state' => $this->state ?? "",
            'username' => $this->username ?? "",
            'password' => $this->password ?? "",
            'ss_password' => $this->ss_password ?? "",
            'ss_port' => $this->ss_port ?? "",
            'ss_method' => $this->ss_method ?? "",
            'ss_link' => $this->ss_password
                ? "ss://{$this->ss_method}:{$this->ss_password}@{$this->ip_address}:{$this->ss_port}"
                : "",
            'ss_link_base64' => $this->ss_password
                ? "ss://" . rtrim(strtr(base64_encode("{$this->ss_method}:{$this->ss_password}"), '+/', '-_'), '=') . "@{$this->ip_address}:{$this->ss_port}"
                : "",
        ];
    }

}
