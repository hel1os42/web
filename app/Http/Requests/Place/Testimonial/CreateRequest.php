<?php

namespace App\Http\Requests\Place\Testimonial;

use App\Models\Testimonial;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     */
    public function rules(): array
    {
        return [
            'text'  => 'string',
            'stars' => 'required|numeric|between:1,5',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!$this->uniqueForUser()) {
                $validator->errors()->add('testimonial', trans('validation.unique_user_testimonial'));
            }
        });
    }

    /**
     * @return bool
     * @throws \InvalidArgumentException
     */
    private function uniqueForUser()
    {
        return Testimonial::query()
                          ->where('user_id', auth()->user()->id)
                          ->where('place_id', request()->route('placeUuid'))
                          ->first() === null;
    }
}
