<?php

namespace App\Http\Requests\Place;

use App\Repositories\PlaceRepository;
use Illuminate\Auth\AuthManager;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateUpdateRequest
 * @package App\Http\Requests
 *
 * @property string name
 * @property string description
 * @property string about
 * @property string address
 * @property float  latitude
 * @property float  longitude
 * @property int    radius
 * @property array  category_ids
 *
 */
class CreateUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @param PlaceRepository $repository
     * @param AuthManager     $authManager
     *
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function authorize(PlaceRepository $repository, AuthManager $authManager)
    {
        $authorized = $repository->existsByUser($authManager->guard()->user());

        if ($this->isMethod('post')) {
            $authorized = !$authorized;
        }

        return $authorized;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'                       => 'required|string|min:3|max:255',
            'description'                => 'string',
            'about'                      => 'string',
            'address'                    => 'string|max:255',
            'category'                   => sprintf(
                'required|string|regex:%s|exists:categories,id',
                \App\Helpers\Constants::UUID_REGEX
            ),
            'retail_types'               => 'required|array',
            'retail_types.*'             => sprintf(
                'string|regex:%s|exists:categories,id',
                \App\Helpers\Constants::UUID_REGEX
            ),
            'latitude'                   => 'required_with:longitude,radius|numeric|between:-90,90',
            'longitude'                  => 'required_with:latitude,radius|numeric|between:-180,180',
            'radius'                     => 'required_with:latitude,longitude|integer|min:1',
            'tags'                       => 'nullable|array',
            'tags.*'                     => 'string|exists:tags,slug',
            'specialities'               => 'nullable|array',
            'specialities.*.retail_type' => 'string|exists:specialities,category_id',
            'specialities.*.specs'       => 'array',
            'specialities.*.specs.*'     => 'string|exists:specialities,slug',
        ];
    }
}
