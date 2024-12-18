<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'image' => $this->image,
            'attributes' => $this->transformAttributes($this->attribute_names, $this->attribute_values),
        ];
    }

    /**
     * Transform attribute names and values into a structured format.
     *
     * @param  string  $attributeNames
     * @param  string  $attributeValues
     * @return array
     */
    protected function transformAttributes($attributeNames, $attributeValues)
    {
        $attributeNames = explode(',', $attributeNames);
        $attributeValues = explode(',', $attributeValues);

        return collect($attributeNames)->map(function ($name, $index) use ($attributeValues) {
            return [
                'name' => trim($name),
                'value' => trim($attributeValues[$index] ?? ''),
            ];
        })->toArray();
    }
}
