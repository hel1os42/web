<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class OfferRequest
 * @package App\Http\Requests\User
 *
 * @property array  category_ids
 * @property string latitude
 * @property string longitude
 * @property int    radius
 *
 */
class OfferRequest extends FormRequest
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
            'category_ids' => 'required|array',
            'category_ids.*' => sprintf(
                'string|regex:%s|exists:categories,id',
                \App\Helpers\Constants::UUID_REGEX
            ),
            'latitude'       => 'required_with:longitude,radius|string|nullable',
            'longitude'      => 'required_with:latitude,radius|string|nullable',
            'radius'         => 'required_with:latitude,longitude|integer|nullable'
        ];
    }
} 
