<?php

namespace App\Http\Requests\Orders;

use App\Traits\OrderQueries;
use App\Rules\IsActiveCoupon;
use App\Rules\SufficientStock;
use Illuminate\Support\Facades\DB;
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
        $rules = [
            'items' => ['required','array','min:1'],
            'items.*.product_id' => ['required','integer','exists:products,id'],
            'items.*.quantity' => ['required','integer','min:1'],
            'shipping_address' => ['required','string','max:255'],
            'code' => ['sometimes' , 'string' , 'max:255' , new IsActiveCoupon($this->getTotalPrice())]
        ];

        foreach ($this->input('items', []) as $key => $item) {
            if (isset($item['product_id'])) {
                $rules["items.$key.quantity"][] = new SufficientStock($item['product_id']);
            }
        }

        return $rules;
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
        return OrderQueries::getPriceByProductId($id);
    }

}
