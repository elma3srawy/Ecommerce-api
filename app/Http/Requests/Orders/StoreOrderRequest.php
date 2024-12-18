<?php

namespace App\Http\Requests\Orders;

use App\Rules\IsActiveCoupon;
use App\Rules\IsActiveProduct;
use App\Rules\SufficientStock;
use App\Traits\ProductQueries;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'items' => ['required','array','min:1'],
            'items.*.product_id' => ['required','integer','exists:products,id', new IsActiveProduct()],
            'items.*.quantity' => ['required','integer','min:1' ,new SufficientStock()],
            'shipping_address' => ['required','string','max:255'],
            'code' => ['sometimes' , 'string' , 'max:255' , new IsActiveCoupon($this->getTotalPrice())]
        ];
    }


    public function getTotalPrice(): float
    {
        return collect($this->input('items'))
        ->unique(fn($item) => $item['product_id'])
        ->reduce(function ($total, $item) {
            return $total + (($item['quantity'] ?? 0) * ($this->getPriceByProductId($item['product_id']) ?? 0));
        }, 0);
    }

    public function getPriceByProductId($id):float
    {
        return ProductQueries::getPriceByProductIdQuery($id);
    }

}
