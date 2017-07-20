<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class OfferRequest
 * @package App\Http\Requests\Auth
 *
 * @property string name
 * @property string description
 * @property int reward
 * @property \Carbon\Carbon dateStart
 * @property \Carbon\Carbon timeStart
 * @property \Carbon\Carbon datefinish
 * @property \Carbon\Carbon timefinish
 * @property string category
 * @property int max_count
 * @property int max_for_user
 * @property int max_per_day
 * @property int max_for_user_per_day
 * @property int min_level
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
            'name' => 'required|string|max:128',
            'description' => '',
            'reward' => ''
        ];
    }
} 
