<?php

namespace App\Http\Requests\Place\Testimonial;

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
     */
    public function rules(): array
    {
        return [
            'text'  => 'required|string|' . $this->getUniqueTextRule(),
            'stars' => 'required|numeric|between:1,5',
        ];
    }

    private function getUniqueTextRule()
    {
        return Rule::unique('testimonials', 'text')
                    ->where('user_id', auth()->user()->id)
                    ->where('place_id', request()->route('placeUuid'));
    }
}
