<?php

namespace App\Http\Requests\Charity;

use App\Enums\Charity\CharityStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rules\Enum;

class UpdateCharityRequest extends FormRequest
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
            'charityId' => ['required'],
            'name' => ['required', "unique:charities,name,{$this->charityId}"],
            'note' => 'nullable',
            'isActive' => ['required', new Enum(CharityStatus::class)],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => $validator->errors()
        ], 401));
    }

    public function messages()
    {
        return [
            'name.unique' => 'اسم الجمعية موجود بالفعل',
        ];
    }

}
