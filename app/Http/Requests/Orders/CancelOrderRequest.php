<?php

namespace App\Http\Requests\Orders;

use App\Traits\OrderQueries;
use App\Traits\OrderItemsQueries;
use Illuminate\Support\Facades\DB;
use App\Events\ProductStockAdjusted;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class CancelOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $order = OrderQueries::getOrderByOrderIdQuery($this->input('order_id'));
        if($order)
        {
            return Gate::allows('user-cancel-order' , $order);
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
        ];
    }

    protected function passedValidation()
    {

        DB::beginTransaction();

        OrderItemsQueries::getOrderItemsByOrderIdQuery($this->input('order_id'))
            ->each(function ($item) {
                event(new ProductStockAdjusted($item->product_id, $item->quantity, 'increment'));
             });

    }
}
