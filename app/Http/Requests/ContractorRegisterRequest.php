<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Auth\User;
use LangleyFoxall\LaravelNISTPasswordRules\PasswordRules;
use Session;
/**
 * Class ContractorRegisterRequest.
 */
class ContractorRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //print_r($this->user()->id);
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
            //'user_id' => ['required', 'string'],
            'mobile_number' => ['required','regex:/^([0-9\s\-\+\(\)]*)$/','min:10', Rule::unique('users')->ignore(Session::get('userId'))],
            'identity_no' => ['required'],
            'payment_method_id' => ['required'],
            
        ];
    }

    /**
     * @return array
     */
            public function messages()
            {
                return [
                 'mobile_number.unique' => __('validation.unique', ['attribute' => 'número de teléfono']),
                   // 'unique1' => 'El campo número de teléfono ya esa en uso',
                ];
            }
}
