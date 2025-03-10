<?php

namespace App\Http\Requests;

use App\Models\Position;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PositionRequest extends FormRequest
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
        $positionId = $this->route('position') ? $this->route('position')->id : null;

        return [
            'name' => ['required', 'string', 'unique:positions'],
            'reports_to' => [
                'nullable', 
                'exists:positions,id',
                function ($attribute, $value, $fail) {
                    if ($value === null) {
                        $existingNullPosition = Position::whereNull('reports_to')->exists();
                        if ($existingNullPosition) {
                            $fail('Only one position.');
                        }
                    }
                }
            ],
        ];
    }
}
