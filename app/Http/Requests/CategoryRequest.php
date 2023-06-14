<?php

namespace App\Http\Requests;

use App\Models\Category;
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $id = $this->route('category');
        return Category::rules($id);

        // or return Category::rules($this->route('category'));
    }

    public function messages()
    {
        return [
            'required' => 'This field (:attribute) is required',
            'unique' => 'This name is already exists!'
        ];
    }
}
