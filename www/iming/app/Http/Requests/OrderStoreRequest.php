<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\PriceRule;

class OrderStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $currency = $this->input('currency');
        return [
            'name' => [
                'required',
                'regex:/^[A-Z][a-zA-Z]*$/'
            ],
            'price' => [
                'required',
                'numeric',
                new PriceRule($currency)
            ]
            'currency' => 'required|in:TWD,USD',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '名稱不可為空。',
            'name.regex' => '名稱須為英文，且第一個字母需大寫。',
            'price.required' => '金額不可為空。',
            'price.numeric' => '金額須應為數字。',
            'price.max' => '金額須小於 2000 新台幣。',
            'currency.required' => '幣值不可為空。',
            'currency.in' => '幣值須為 TWD 或是 USD。',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'errors' => $validator->errors(),
        ], 400);

        throw new HttpResponseException($response);
    }
}
