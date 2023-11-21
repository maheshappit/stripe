<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserFrontLoginWithOTPFormRequest extends CoreRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function rules()
    {
        return [
            'email' => 'required|email|max:100'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => __('Email is required'),
            'email.email' => __('The email must be a valid email address')
        ];
    }
}