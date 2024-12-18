<?php

namespace App\Http\Requests\Reviews;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
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
            'product_id' => ['required' , 'integer' , 'exists:products,id'],
            'rating' => ['required' ,'numeric' , 'min:1' , 'max:5'],
            'review' => ['sometimes' , 'string', 'max:10000']
        ];
    }
}
