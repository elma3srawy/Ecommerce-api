<?php

namespace App\Http\Requests;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
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
        return match (Str::substr($routeName = Route::currentRouteName() , Str::position($routeName , '.') + 1)) {
            'coupon.store'=> $this->onStore(),
            'coupon.update'=> $this->onUpdate(),
            'coupon.destroy'=> $this->onDestroy(),
            default => [],
        };
    }

    public function onStore():array
    {
        return [
            'code' => ['required', 'string', 'max:255', 'unique:coupons,code'], 
            'discount_type' => ['required', 'in:percentage,fixed'], 
            'value' => ['required', 'numeric', 'min:0'], 
            'minimum_order_value' => ['nullable', 'numeric', 'min:0'], 
            'start_date' => ['nullable', 'date', 'after_or_equal:today'], 
            'end_date' => ['nullable', 'date', 'after:start_date'], 
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'usage_count' => ['nullable', 'integer', 'min:0' , 'lt:usage_limit'], 
            'is_active' => ['nullable', 'boolean'],
        ];
    }
    public function onUpdate():array
    {
        return [
            'id' => ['required' , 'integer' , 'exists:coupons,id'],
            'code' => ['required', 'string', 'max:255', 'unique:coupons,code,'.$this->id.',id'],
            'discount_type' => ['required', 'in:percentage,fixed'], 
            'value' => ['required', 'numeric', 'min:0'],
            'minimum_order_value' => ['nullable', 'numeric', 'min:0'],
            'start_date' => ['nullable', 'date', 'after_or_equal:today'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'usage_count' => ['nullable', 'integer', 'min:0' , 'lte:usage_limit'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
    public function onDestroy():array
    {
        return [
            'id' => ['required' , 'integer' , 'exists:coupons,id'],
        ];
    }
}
