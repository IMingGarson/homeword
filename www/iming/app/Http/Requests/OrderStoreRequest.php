<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use App\Enums\CurrrencyType;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\PriceRule;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Exceptions\HttpResponseException;

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
            'id' => [
                'required',
                'regex:/^[A-Za-z0-9_-]*$/'
            ],
            'address' => [
                'required',
                'array',
            ],
            'address.city' => ['required', 'regex:/^[A-Za-z0-9_-]*$/'],
            'address.district' => ['required', 'regex:/^[A-Za-z0-9_-]*$/'],
            'address.street' => ['required', 'regex:/^[A-Za-z0-9_-]*$/'],
            'name' => [
                'required',
                'regex:/^[a-zA-Z ]+$/'
            ],
            'price' => [
                'required',
                'numeric',
                new PriceRule($currency)
            ],
            'currency' => 'required|in:TWD,USD',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name contains non-English characters',
            'name.regex' => 'Name is not capitalized',
            'price.required' => 'Price is required',
            'price.numeric' => 'Price is a number',
            'currency.required' => 'Currency is required',
            'currency.in' => 'Currency format is wrong',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 400));
    }
}
