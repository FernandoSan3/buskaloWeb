<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use LangleyFoxall\LaravelNISTPasswordRules\PasswordRules;
use Session;
/**
 * Class CompanyRegisterRequest.
 */
class CompanyRegisterRequest extends FormRequest
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
            // 'username' => ['required', 'string'],
            'address' => ['required'],
            'ruc_no' => ['required'],
            'legal_representative' => ['required'],
            'payment_method_id' => ['required'],
           // 'user_id' => ['required', 'string'],
             'mobile_number' => ['required','regex:/^([0-9\s\-\+\(\)]*)$/','min:10', Rule::unique('users')->ignore(Session::get('userId'))],
        ];
    }

    /**
     * @return array
     */
            public function messages()
            {
                return [
                 'mobile_number.unique' => __('validation.unique', ['attribute' => 'número de teléfono']),
                    //'unique1' => 'El campo número de teléfono ya esa en uso',
                ];
            }
}
