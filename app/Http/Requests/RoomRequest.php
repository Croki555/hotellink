<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoomRequest extends FormRequest
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
            'start_date' => [
                'nullable',
                'date_format:Y-m-d',
                'required_with:end_date',
            ],
            'end_date' => [
                'nullable',
                'date_format:Y-m-d',
                'required_with:start_date',
                'after:start_date',
                function ($attribute, $value, $fail) {
                    $startDate = $this->input('start_date');
                    if ($startDate && $value <= $startDate) {
                        $fail('Дата окончания должна быть хотя бы на 1 день больше даты начала');
                    }
                },
            ]
        ];
    }
}
