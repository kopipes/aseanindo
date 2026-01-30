<?php

namespace App\Http\Requests\Api\ScheduleBot;

use App\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class BookingProductScheduleRequest extends FormRequest
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
        return [
            'forms' => 'required|array',
            'product_id' => 'required',
            'name' => 'required',
            'email' => 'required',
            'phone_number' => 'required',
            'phone_number_code' => 'required',
            'subject_id' => 'required',
            'product_category' => 'required'
        ];
    }
}
