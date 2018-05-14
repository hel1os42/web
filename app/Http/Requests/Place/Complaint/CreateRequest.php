<?php

namespace App\Http\Requests\Place\Complaint;

use App\Models\Complaint;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class CreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'text' => 'required|string|min:3|max:160',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  Validator $validator
     *
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function (Validator $validator) {
            if (!$this->uniqueForUser()) {
                $validator->errors()->add('compliant', trans('validation.unique_user_complaint'));
            }
        });
    }

    /**
     * @return bool
     * @throws \InvalidArgumentException
     */
    private function uniqueForUser()
    {
        return Complaint::query()
                        ->where('user_id', auth()->user()->id)
                        ->where('place_id', request()->route('placeUuid'))
                        ->first() === null;
    }
}
