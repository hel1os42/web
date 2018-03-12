<?php

namespace App\Http\Requests\User\Favorite;

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
            'offer_id' =>
                sprintf('required|string|regex:%s|uniqueBy2Fields:users_favorite_offers,user_id,%s',
                    \App\Helpers\Constants::UUID_REGEX,
                    \request()->segment(2) !== 'favorite' ? \request()->segment(2) : auth()->user()->getId()
                )
        ];
    }

    public function messages()
    {
        return [
            'unique_by2_fields' => 'You already add this offer to favorite.'
        ];
    }
} 
