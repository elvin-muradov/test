<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function messages()
    {
        return [
            'required' => 'The :attribute field is required',
            'max' => 'The :attribute field must contain maximum :max characters',
            'min' => 'The :attribute field must contain minimum :min characters',
            'image' => 'The file type must be image',
            'image.mimes' => 'The image type must be :mimes',
            'cat.exists' => 'Please select category',
        ];
    }

    public function rules()
    {
        return [
            'title' => ['required', 'min:6', 'max:255'],
            'content' => ['required', 'min:6'],
            'cat' => ['required', 'exists:categories,id'],
            'tags' => ['required', 'array', 'min:1'],
            'tags.*' => ['required', 'exists:tags,id'],
            'image' => ['image', 'mimes:jpg,png,jpeg,bmp', 'max:3072'],
        ];
    }
}
