<?php

namespace App\Http\Requests\CallnChat\Chat;

use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest
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
        $category = $this->route()->parameter('category');
        switch ($category) {
            case 'message':
                return [
                    'message' => 'required',
                    'user_name' => 'required',
                    'user_profile' => 'required',
                    'destination' => 'required'
                ];
            case 'location':
                return [
                    'message.address' => 'required',
                    'message.lat' => 'required',
                    'message.lng' => 'required',
                    'user_name' => 'required',
                    'user_profile' => 'required',
                    'destination' => 'required'
                ];
            case 'file':
                return [
                    'message' => 'required|file',
                    'user_name' => 'required',
                    'user_profile' => 'required',
                    'destination' => 'required'
                ];
        }
        return [
        ];
    }
}