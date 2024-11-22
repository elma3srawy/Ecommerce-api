<?php

namespace App\Http\Requests;

use App\Rules\ValidParentCategory;
use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
        return [];
    }

    public function onStore():array
    {
        return $this->validate([
            'parent_id' => ['sometimes','numeric','exists:categories,id'],
            'name' => ['required','string' , 'unique:categories,name', 'max:255' ],
            'image' => ['sometimes','file' , 'image' ,'mimes:jpeg,png', 'max:1024'],
            'status' => ['boolean' , 'in:1,0'],
            'description' => ['sometimes' , 'string', 'max:500']
        ]);
    }
    public function onUpdate()
    {
        return $this->validate([
            'id' => ['required' ,'numeric' , 'exists:categories,id'],
            'parent_id' => ['sometimes','numeric','exists:categories,id' , new ValidParentCategory($this->id)],
            'name' => ['required', 'string' , 'unique:categories,name,'. $this->id ,'max:255'],
            'image' => ['sometimes','file' , 'image' ,'mimes:jpeg,png', 'max:1024'],
            'status' => ['boolean' , 'in:1,0'],
            'description' => ['sometimes' , 'string', 'max:500']
        ]);
    }
    public function onDelete()
    {
        return $this->validate(['category_id'  => 'required|string|exists:categories,id']);
    }
}
