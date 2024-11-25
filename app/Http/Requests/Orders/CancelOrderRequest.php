<?php

namespace App\Http\Requests\Orders;

use App\Traits\OrderQueries;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class CancelOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $status = OrderQueries::getColumnByOrderIdQuery($this->input('order_id' ) , 'order_status');

        return Gate::allows('user-cancel-order' ,$status);
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
}
