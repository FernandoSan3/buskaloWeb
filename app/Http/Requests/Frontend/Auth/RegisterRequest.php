<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use LangleyFoxall\LaravelNISTPasswordRules\PasswordRules;

/**
 * Class RegisterRequest.
 */
class RegisterRequest extends FormRequest
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
            'username' => ['required', 'string'],
            'mobile_number' => ['required','regex:/^([0-9\s\-\+\(\)]*)$/','min:10', Rule::unique('users')],
            'user_group_id' => ['required'],
            'email' => ['required', 'string', 'email', Rule::unique('users')],
            'password' => 'required|min:8|confirmed',
           // 'password' => PasswordRules::register($this->email),
            'g-recaptcha-response' => ['required_if:captcha_status,true', 'captcha'],
            //'g-recaptcha-response' => ['required'],
        ];
    }

    /**
     * @return array
     */
             public function messages()
             {
                 return [
                    'g-recaptcha-response.required_if' => __('validation.required', ['attribute' => 'captcha']),
                    'mobile_number.unique' => __('validation.unique', ['attribute' => 'número de teléfono']),
                    //'unique' => 'El campo :attribute ya está en uso.',
                   // 'unique' => 'El campo número de teléfono ya esa en uso',
                   // 'mobile_number.required' => 'El campo número de teléfono ya esa en uso',
                   // 'password'=>trans('hrll')
                 ];
             }
}
