<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use LangleyFoxall\LaravelNISTPasswordRules\PasswordRules;

/**
 * Class StoreUserRequest.
 */
class StoreContractorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => ['required'],
            'email' => ['required', 'email', Rule::unique('users')],
            'password' => PasswordRules::register($this->email),
            'mobile_number' => ['required'],
            'landline_number' => ['required'],
            'address' => ['required'],
            'profile_description' => ['required'],            
            'facebook_url' => ['required'],            
            'instagram_url' => ['required'],            
            'snap_chat_url' => ['required'],            
            'twitter_url' => ['required'],            
            'youtube_url' => ['required'],            
        ];
    }
}
