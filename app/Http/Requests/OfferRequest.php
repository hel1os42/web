<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class OfferRequest
 * @package App\Http\Requests\Auth
 *
 * @property string label
 * @property string description
 * @property int reward
 * @property \Carbon\Carbon start_date
 * @property \Carbon\Carbon start_time
 * @property \Carbon\Carbon finish_date
 * @property \Carbon\Carbon finish_time
 * @property string category
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'label' => 'required|string|max:128',
            'description' => 'required|nullable|string',
            'reward' => 'required|integer',
            'start_date' => 'required|string',
            'start_time' => 'required|string',
            'finish_date' => 'required|nullable|string',
            'finish_time' => 'required|nullable|string',
            'category' => 'required|nullable|string',
            'max_count' => 'required|nullable|integer',
            'max_for_user' => 'required|nullable|integer',
            'max_per_day' => 'required|nullable|integer',
            'max_for_user_per_day' => 'required|nullable|integer',
            'user_level_min' => 'required|nullable|integer',
            'latitude' => 'required|nullable|string',
            'longitude' => 'required|nullable|string',
            'radius' => 'required|nullable|integer',
            'country' => 'required|nullable|string',
            'city' => 'required|nullable|string'
        ];
    }
} 
