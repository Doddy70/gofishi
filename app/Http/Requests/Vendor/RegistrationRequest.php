<?php

namespace App\Http\Requests\Vendor;

use App\Models\Admin;
use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        $admin = Admin::select('username')->first();
        $admin_username = $admin->username;
        $bs = \App\Models\BasicSettings\Basic::first();

        $rules = [
            'username' => "required|unique:vendors|not_in:$admin_username",
            'email' => 'required|email|unique:vendors',
            'phone' => 'required',
            'password' => 'required|confirmed|min:6',
            'dob' => 'required|date',
            'terms_and_conditions' => 'required',
            'age_agreement' => 'accepted',
            'ktp_file' => 'required|mimes:jpeg,jpg,png,pdf|max:2048',
            'boat_ownership_file' => 'required|mimes:jpeg,jpg,png,pdf|max:2048',
            'driving_license_file' => 'required|mimes:jpeg,jpg,png,pdf|max:2048',
            'self_photo_file' => 'required|mimes:jpeg,jpg,png,pdf|max:2048',
        ];

        if ($bs->google_recaptcha_status == 1) {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages()
    {
        return [
            'username.required' => __('The username field is required.'),
            'username.unique' => __('This username is already taken.'),
            'username.not_in' => __('The username cannot be the same as the admin username.'),
            'email.required' => __('The email field is required.'),
            'email.email' => __('Please provide a valid email address.'),
            'email.unique' => __('This email is already registered.'),
            'phone.required' => __('The phone number field is required.'),
            'password.required' => __('The password field is required.'),
            'password.confirmed' => __('The password confirmation does not match.'),
            'password.min' => __('The password must be at least :min characters.'),
            'dob.required' => __('The date of birth field is required.'),
            'terms_and_conditions.required' => __('You must agree to the Terms & Conditions.'),
            'age_agreement.accepted' => __('Anda harus menyatakan bahwa Anda berusia di atas 17 tahun.'),
            'ktp_file.required' => __('KTP document is required.'),
            'boat_ownership_file.required' => __('Boat ownership document is required.'),
            'driving_license_file.required' => __('Driver license is required.'),
            'self_photo_file.required' => __('Self photo is required.'),
            'ktp_file.max' => __('KTP document size must be under 2MB.'),
            'boat_ownership_file.max' => __('Boat ownership document size must be under 2MB.'),
            'driving_license_file.max' => __('Driver license size must be under 2MB.'),
            'self_photo_file.max' => __('Self photo size must be under 2MB.'),
        ];
    }
}
