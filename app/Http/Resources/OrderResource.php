<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'grand_total'   => $this->grand_total,
            'shipping_cost' => $this->shipping_cost,
            'discount'      => $this->discount,
            'items'         => OrderDetailResource::collection($this->whenLoaded('orderDetails')),
        ];
    }
}
