<?php

namespace App\Http\Requests;

use App\Repositories\ActivationCodeRepository;
use Illuminate\Auth\AuthManager;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class RedemptionRequest
 * @package App\Http\Requests
 *
 * @property string code
 *
 */
class RedemptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @param ActivationCodeRepository $activationCodeRepository
     *
     * @param AuthManager              $authManager
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
            'code' => 'required|can_redeem|min:3'
        ];
    }
} 
