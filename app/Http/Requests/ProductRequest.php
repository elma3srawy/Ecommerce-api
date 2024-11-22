<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

    public function onStore()
    {
        return $this->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['sometimes', 'string', 'max:1000'],
            'price' => ['required', 'numeric', 'min:0', 'max:100000000'],
            'stock_quantity' => ['required', 'integer', 'min:0', 'max:100000000'],
            'image' => ['sometimes', 'file', 'image', 'mimes:jpg,png,jpeg', 'max:1042'],
            'product_attributes' => ['required', 'array'],
            'product_attributes.*.attribute_name' => ['required', 'string', 'max:255'],
            'product_attributes.*.attribute_value' => ['required', 'string', 'max:255'],
        ]);
    }
    public function onUpdate()
    {
       return $this->validate([
            'product_id' => ['required' , 'integer' , 'exists:products,id'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['sometimes', 'string', 'max:1000'],
            'price' => ['required', 'numeric', 'max:100000000'],
            'stock_quantity' => ['required', 'integer', 'min:0', 'max:100000000'],
            'image' => ['sometimes', 'file', 'image', 'mimes:jpg,png,jpeg', 'max:1042'],
            'product_attributes' => ['required', 'array'],
            'product_attributes.*.attribute_name' => ['required', 'string', 'max:255'],
            'product_attributes.*.attribute_value' => ['required', 'string', 'max:255'],
        ]);
    }
    public function onDelete()
    {
        return $this->validate(['product_id' => ['required' , 'integer' , 'exists:products,id']]);
    }
    public function onChangePrice()
    {
        return $this->validate([
            'product_id' => ['required' , 'integer' , 'exists:products,id'],
            'new_price' => ['required', 'numeric', 'max:100000000'],
        ]);
    }

    public function onChangeImage()
    {
        return $this->validate([
            'product_id' => ['required' , 'integer' , 'exists:products,id'],
            'image' => ['required', 'file', 'image', 'mimes:jpg,png,jpeg', 'max:1042'],
        ]);
    }
}
