<?php

namespace App\Http\Resources\Products;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductPricingHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         $data = [
            'product_id' => $this->product_id,
            'old_price' => $this->old_price,
            'new_price' => $this->new_price,
            'changed_at' => $this->changed_at,
            'admin_id' => $this->admin_id,
            'admin_name' => $this->admin_name,
            'staff_id' => $this->staff_id,
            'staff_name' => $this->staff_name,
        ];

        return array_filter($data, function($value) {
            return !is_null($value);
        });
    }
}
