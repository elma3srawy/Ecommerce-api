<?php

namespace App\Http\Requests\Orders;

use App\Traits\OrderQueries;
use App\Traits\OrderItemsQueries;
use Illuminate\Support\Facades\DB;
use App\Events\ProductStockAdjusted;
use Illuminate\Foundation\Http\FormRequest;

class ChangeOrderStatusRequest extends FormRequest
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
            'order_id' => ['required', 'integer' ,'exists:orders,id'],
            'status' => ['required' , 'string' , 'in:pending,cancelled,completed,shipped']
        ];
    }

    protected function passedValidation()
    {
        DB::beginTransaction();

        $old_status = OrderQueries::getColumnByOrderIdQuery($this->order_id , 'order_status');

        if($this->status <> $old_status && $old_status === 'cancelled'){
            $action = "decrement";
        }elseif($this->status <> $old_status && $this->status === 'cancelled'){
            $action = "increment";
        }else{
            return;
        }

        OrderItemsQueries::getOrderItemsByOrderIdQuery($this->input('order_id'))
            ->each(function ($item) use($action) {
                event(new ProductStockAdjusted($item->product_id, $item->quantity, $action));
             });

    }
}
