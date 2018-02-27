<?php

namespace App\Http\Requests\Tag;

use App\Helpers\Constants;
use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateUpdateRequest
 * @package App\Http\Requests\Auth
 *
 * @property string category_id
 * @property string name
 * @property string slug
 *
 */
class CreateUpdateRequest extends FormRequest
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
            'category_id' => sprintf('required|string|regex:%s|in:%s|uniqueCategoryIdAndSlug:%s,%s',
                Constants::UUID_REGEX,
                implode(',', array_column(Category::withNoParent()->get()->toArray(), 'id')),
                $this->slug,
                $this->segment(2)),
            'name'      => 'required|string|min:2|max:191',
            'slug'      => 'required|string|min:2|max:191|regex:/[a-z0-9-]+/'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'category_id.unique_category_id_and_slug' => 'Fields category+slug must be unique.',
        ];
    }
} 
