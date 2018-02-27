<?php

namespace App\Http\Requests\Category;

use App\Helpers\Constants;
use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateUpdateRequest
 * @package App\Http\Requests\Auth
 *
 * @property string parent_id
 * @property string name
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
            // stub: only retail types(subcategory)
            'parent_id' => sprintf('required|string|regex:%s|in:%s',
                Constants::UUID_REGEX,
                implode(',', array_column(Category::withNoParent()->get()->toArray(), 'id'))),
            'name'      => sprintf('required|string|min:2|max:191|unique:categories,name,%s,id', $this->segment(2))
        ];
    }
} 
