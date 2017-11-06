<?php

namespace App\Http\Requests\Offer;

use App\Models\NauModels\Offer;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'status' => 'required|string|in:' . implode(',', [Offer::STATUS_ACTIVE, Offer::STATUS_DEACTIVE]),
        ];
    }
}
