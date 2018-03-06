<?php

namespace App\Http\Requests\OfferLink;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;
use Symfony\Component\HttpFoundation\Request;

class WriteRequest extends FormRequest
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
            'tag'         => 'required|string|min:3|max:255|' . $this->getUniqueTagRule(),
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'tag'         => trans('offer_links.fields.tag'),
            'title'       => trans('offer_links.fields.title'),
            'description' => trans('offer_links.fields.description'),
        ];
    }

    private function getUniqueTagRule()
    {
        $rule = Rule::unique('offer_links', 'tag')
            ->where('place_id', request()->route('placeUuid'));

        if (Request::METHOD_PUT === request()->getMethod()) {
            $rule->ignore(array_last(request()->segments()));
        }

        return $rule;
    }
}
