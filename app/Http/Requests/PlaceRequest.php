<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class PlaceRequest
 * @package App\Http\Requests
 *
 * @property string name
 * @property string description
 * @property string about
 * @property string address
 * @property float latitude
 * @property float longitude
 * @property int radius
 *
 */
class PlaceRequest extends FormRequest
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
            'name'        => 'required|string|min:3',
            'description' => 'string',
            'about'       => 'string',
            'address'     => 'string',
            'latitude'    => 'required|numeric|min:-90|max:90',
            'longitude'   => 'required|numeric|min:-180|max:180',
            'radius'      => 'required|numeric|min:1',
        ];
    }
} 
