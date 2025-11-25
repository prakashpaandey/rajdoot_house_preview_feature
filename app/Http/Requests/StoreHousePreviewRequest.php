<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHousePreviewRequest extends FormRequest
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
            
            'customer' => ['required', 'array'],
            'customer.name' => ['required', 'string', 'min:4', 'max:255'],
            'customer.phone' => ['required', 'string', 'size:10', 'regex:/^[0-9]{10}$/'],
            'customer.address' => ['required', 'string', 'min:4'],
            'colors' => ['nullable', 'string'],
            'png_image' => ['required', 'image', 'mimes:png', 'max:10240'],
            'svg_image' => ['nullable', 'file', 'mimes:svg', 'max:5120'],
            'customer_message' => ['nullable', 'string'],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'customer.required' => 'Customer information is required.',
            'customer.name.required' => 'Customer name is required.',
            'customer.name.min' => 'Customer name must be at least 4 characters.',
            'customer.phone.required' => 'Contact number is required.',
            'customer.phone.size' => 'Phone number must be exactly 10 digits.',
            'customer.phone.regex' => 'Phone number must contain only digits (0-9).',
            'customer.address.required' => 'Address is required.',
            'customer.address.min' => 'Address must be at least 4 characters.',
            
            'png_image.required' => 'House image is required.',
            'png_image.image' => 'The file must be an image.',
            'png_image.mimes' => 'Image must be in PNG format.',
            'png_image.max' => 'Image must not exceed 10MB.',
            
            'svg_image.mimes' => 'SVG file must be in SVG format.',
            'svg_image.max' => 'SVG file must not exceed 5MB.',
        ];
    }
}
