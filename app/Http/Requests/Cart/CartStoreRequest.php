<?php

namespace App\Http\Requests\Cart;

use App\Rules\IsActiveProduct;
use Illuminate\Foundation\Http\FormRequest;

class CartStoreRequest extends FormRequest
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
            'cart' => ['required' ,'array', 'min:1'],
            'cart.*.product_id' => ['required' , 'integer' , 'exists:products,id' , new IsActiveProduct()],
            'cart.*.quantity' => ['required' , 'integer']
        ];
    }
}
