<?php

namespace App\Http\Resources\Products;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'price' => (float)$this->price,
            'stock_quantity' => (int)$this->stock_quantity,
            'attribute_names' => explode(',', $this->attribute_names),
            'attribute_values' => explode(',', $this->attribute_values),
            'image' => $this->image ? $this->image : 'not found',
            'category_name' => $this->category_name,
            'category_status' => $this->category_status == 1 ? 'active' : 'inactive'
        ];
    }
}
