<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class OfferRequest
 * @package App\Http\Requests\Auth
 *
 * @property string name
 * @property string description
 * @property string category
 * @property string latitude
 * @property string longitude
 * @property int radius
 * @property string country
 * @property string city
 *
 */
class SearchOfferRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'radius' => 'required|integer'
        ];
    }
} 
