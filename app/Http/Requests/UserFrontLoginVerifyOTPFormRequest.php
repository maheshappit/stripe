<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserFrontLoginVerifyOTPFormRequest extends CoreRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function rules()
    {
        return [
            'otp' => 'required|min:6|max:6'
        ];
    }

    public function messages()
    {
        return [
            'otp.required' => __('OTP is required')
        ];
    }
}