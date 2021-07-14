<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferFormValidate extends FormRequest
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
            'to_phone'=>'required',
            'amount'=>'required|numeric|min:1000',
        ];
    }
    public function messages()
    {
        return [
            'to_phone.required' => 'ငွေလွဲမည့်ဖုန်းနံပါတ် ထည့်ရန်လိုအပ်ပါသည်။',
            'amount.required' => 'ငွေလွဲမည့်ပမာဏ ထည့်ရန်လိုအပ်ပါသည်။',

        ];
    }
}
