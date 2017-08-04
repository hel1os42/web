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
            'description'          => 'required|nullable|string',
            'reward'               => 'required|numeric',
            'start_date'           => 'required|date|date_format:Y-m-d\TH:i:sO',
            'finish_date'          => 'required|date|date_format:Y-m-d\TH:i:sO',
            'start_time'           => 'required|nullable|date|date_format:H:i:sO',
            'finish_time'          => 'required|nullable|date|date_format:H:i:sO',
            'category_id'          => 'required|string|exists:categories,id',
            'max_count'            => 'required|nullable|integer',
            'max_for_user'         => 'required|nullable|integer',
            'max_per_day'          => 'required|nullable|integer',
            'max_for_user_per_day' => 'required|nullable|integer',
            'user_level_min'       => 'required|nullable|integer',
            'latitude'             => 'required|nullable|string',
            'longitude'            => 'required|nullable|string',
            'radius'               => 'required|nullable|integer',
            'country'              => 'required|nullable|string',
            'city'                 => 'required|nullable|string'
        ];
    }
} 
