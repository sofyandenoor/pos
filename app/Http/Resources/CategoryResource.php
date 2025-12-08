<?php

namespace App\Http\Resources;

use Illuminate\Foundation\Http\FormRequest;

class CategoryResource extends FormRequest
{
    public function authorize(): bool   
    {
        return false;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:categories,name,'.$this->route('category'),
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'tagline' => 'required|string|max:255',
        ];
    }
}