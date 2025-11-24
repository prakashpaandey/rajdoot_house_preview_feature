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
            'customer.name' => ['required', 'string', 'max:255'],
            'customer.phone' => ['required', 'string', 'max:20'],
            'customer.address' => ['required', 'string'],
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
            'customer.phone.required' => 'Contact number is required.',
            'customer.email.email' => 'Please provide a valid email address.',
            'customer.address.required' => 'Address is required.',
            
            'png_image.image' => 'PNG file must be a valid image.',
            'png_image.mimes' => 'PNG file must be in PNG format.',
            'png_image.max' => 'PNG image must not exceed 10MB.',
            
            'svg_image.mimes' => 'SVG file must be in SVG format.',
            'svg_image.max' => 'SVG file must not exceed 5MB.',
        ];
    }
}
