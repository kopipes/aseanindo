<?php

namespace App\Http\Requests\Api\Chat;

use App\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest
{
    use FailedValidation;
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
        $category = $this->category;
        switch ($category) {
            case 'message':
                return [
                    'category' => 'required|in:message',
                    'company_id' => 'required',
                    'message' => 'required',
                    'destination' => 'required'
                ];
            case 'location':
                return [
                    'category' => 'required|in:location',
                    'company_id' => 'required',
                    // 'message.address' => 'required',
                    'message.lat' => 'required',
                    'message.lng' => 'required',
                    'destination' => 'required'
                ];
            case 'file':
                return [
                    'category' => 'required|in:file',
                    'company_id' => 'required',
                    'message' => 'required|file',
                    'destination' => 'required'
                ];
        }
        return [
            'category' => 'required|in:message,file,location',
        ];
    }
}
