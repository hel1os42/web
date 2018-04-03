<?php

namespace App\Http\Requests\Place\Testimonial;

use App\Models\Testimonial;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'text'  => 'string',
            'stars' => 'numeric|between:1,5',
            'status' => 'required|string|regex:/[' . implode(',', Testimonial::getAllStatuses()) . ']/',
        ];
    }
}
