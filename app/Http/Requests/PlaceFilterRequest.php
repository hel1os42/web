<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class PlaceFilterRequest
 * @package App\Http\Requests
 *
 * @property array category_ids
 * @property string latitude
 * @property string longitude
 * @property int radius
 *
 */
class PlaceFilterRequest extends FormRequest
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
            'category_ids'   => 'required|array',
            'category_ids.*' => sprintf(
                'string|regex:%s|exists:categories,id',
                \App\Helpers\Constants::UUID_REGEX
            ),
            'latitude'       => 'required|numeric|between:-90,90',
            'longitude'      => 'required|numeric|between:-180,180',
            'radius'         => 'integer|nullable'
        ];
    }
} 
