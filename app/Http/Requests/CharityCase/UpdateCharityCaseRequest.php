<?php

namespace App\Http\Requests\CharityCase;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rules\Enum;
class UpdateCharityCaseRequest extends FormRequest
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
        return [
            'charityCaseId' => 'required',
            'nationalId' => ['required', "unique:charity_cases,national_id,$this->charityCaseId'"],
            'name' => 'required',
            'phone' => 'nullable',
            'address' => 'nullable',
            'socialStatus' => ['required'],
            'gender' => ['required'],
            'dataOfBirth' => ['nullable'],
            'note' => ['nullable']
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => $validator->errors()
        ], 401));
    }

}
