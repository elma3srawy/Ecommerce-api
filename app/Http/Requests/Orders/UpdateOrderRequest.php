<?php

namespace App\Http\Requests\Orders;

use App\Models\Order;
use App\Traits\OrderQueries;
use App\Rules\SufficientStock;
use App\Traits\OrderItemsQueries;
use Illuminate\Support\Facades\DB;
use App\Events\ProductStockAdjusted;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize():bool
    {
        $order = OrderQueries::getOrderByOrderIdQuery($this->input('order_id'));
        if($order)
        {
            return Gate::allows('user-update-order' , $order);
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'order_id' => ['required' , 'integer' , 'exists:orders,id'],
            'items' => ['required','array','min:1'],
            'items.*.product_id' => ['required','integer','exists:products,id'],
            'items.*.quantity' => ['required','integer','min:1' ,new SufficientStock()],
            'shipping_address' => ['required','string','max:255'],
        ];
    }

    protected function prepareForValidation()
    {
        DB::beginTransaction();

        if ($this->has('order_id')) {
            OrderItemsQueries::getOrderItemsByOrderIdQuery($this->input('order_id'))
                ->each(function ($item) {
                    event(new ProductStockAdjusted($item->product_id, $item->quantity, 'increment'));
                });
        }
    }
}
