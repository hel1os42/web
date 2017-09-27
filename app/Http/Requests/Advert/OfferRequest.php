<?php

namespace App\Http\Requests\Advert;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class OfferRequest
 * @package App\Http\Requests\Auth
 *
 * @property string label
 * @property string description
 * @property float reward
 * @property \Carbon\Carbon start_date
 * @property \Carbon\Carbon start_time
 * @property \Carbon\Carbon finish_date
 * @property \Carbon\Carbon finish_time
 * @property string category_id
 * @property int max_count
 * @property int max_for_user
 * @property int max_per_day
 * @property int max_for_user_per_day
 * @property int user_level_min
 * @property string latitude
 * @property string longitude
 * @property int radius
 * @property string country
 * @property string city
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
            'label'                => 'required|string|min:3|max:128',
            'description'          => 'string',
            'reward'               => 'required|numeric|min:1',
            /*'start_date'           => 'required|date|date_format:Y-m-d H:i:s.uO',
            'finish_date'          => 'date|date_format:Y-m-d H:i:s.uO',
            'start_time'           => 'required|date_format:H:i:s.uO',
            'finish_time'          => 'date_format:H:i:s.uO',
            */'category_id'          => 'required|string|exists:categories,id',
            'max_count'            => 'integer|min:1',
            'max_for_user'         => 'integer|min:1',
            'max_per_day'          => 'integer|min:1',
            'max_for_user_per_day' => 'integer|min:1',
            'user_level_min'       => 'integer|min:1',
            'latitude'             => 'numeric|between:-90,90',
            'longitude'            => 'numeric|between:-180,180',
            'radius'               => 'integer',
            'country'              => 'string',
            'city'                 => 'string'
        ];
    }
} 
