<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
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
           'old_password'=>'required',
           'new_password'=>'required|min:6'
        ];
    }
    public function messages()
    {
        return [
            'old_password.required' => 'လက်ရှိစကား၀ှက် ထည့်ရန်လိုအပ်ပါသည်။',
            'new_password.required' => 'စကား၀ှက်အသစ် ထည့်ရန်လိုအပ်ပါသည်။',
            'new_password.min' => 'စကား၀ှက်အသစ်သည် အနည်းဆုံး ၆ လုံးထည့်ရန်လိုအပ်ပါသည်။',

        ];
    }
}
