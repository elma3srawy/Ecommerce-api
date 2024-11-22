<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CouponUsersRequest extends FormRequest
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
        if($this->isMethod('post')){
            return $this->onStore();
        }elseif($this->isMethod('put')){
            return $this->onUpdate();
        }else{
            return $this->onDestroy();
        }
    }
    private function onStore(): array
    {
        return [
            'coupons' => ['required', 'array'],
            'coupons.*.coupon_id' => [
                'required',
                'integer',
                'exists:coupons,id',
                Rule::unique('coupon_user')->where(function ($query) {
                    return $query->where('user_id', $this->input('coupons.*.user_id'));
                })
            ],
            'coupons.*.user_id' => [
                'required',
                'integer',
                'exists:users,id',
                Rule::unique('coupon_user')->where(function ($query) {
                    return $query->where('coupon_id', $this->input('coupons.*.coupon_id'));
                })
            ],
        ];
    }
    private function onUpdate(): array
    {
        return [
            'coupon_id' => ['required' , 'integer' , 'exists:coupons,id'] ,
            'user_ids' => ['required' , 'array' , 'exists:users,id'] ,
        ];
    }
    public function onDestroy()
    {
        return [
            'coupon_id' => ['required' , 'integer' , 'exists:coupon_user,coupon_id']
        ];
    }
    public function messages()
    {
        return [
            'coupons.*.coupon_id.required' => 'The coupon ID is required.',
            'coupons.*.coupon_id.unique' => 'This coupon has already been assigned to this user.',
            'coupons.*.user_id.required' => 'The user ID is required.',
            'coupons.*.user_id.unique' => 'This user has already been assigned this coupon.',
        ];
    }
}
