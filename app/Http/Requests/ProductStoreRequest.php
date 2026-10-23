<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' =>'required|string|max:255',
            'description' =>"nullable|string|max:2000",
            'stock_quantity' =>'required|integer|min:0',
'price' => 'required|numeric|min:0.01|decimal:0,2',
'category_id' => [
    'required',
    Rule::exists('categories', 'id')
        ->whereNotNull('parent_id'),
],   
'images' => 'nullable|array|max:4',

'images.*' => 'image|max:2048',
'primary_image' =>'required|image|max:2048'

];
    }
}
