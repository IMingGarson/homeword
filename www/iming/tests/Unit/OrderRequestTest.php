<?php

namespace Tests\Unit;

use App\Http\Requests\OrderStoreRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;
use App\Rules\PriceRule;

class OrderRequestTest extends TestCase
{
    public function test_valide_order_store_request()
    {
        $request = new OrderStoreRequest();
        $rules = $request->rules();

        $validator = Validator::make([
            'name' => 'ProductName',
            'price' => 1599,
            'currency' => 'TWD'
        ], $rules);

        $this->assertTrue($validator->passes());
    }

    public function test_non_captialized_first_letter_in_name()
    {
        $request = new OrderStoreRequest();
        $rules = $request->rules();
        $validator = Validator::make([
            'name' => 'productname', // non-capitalized first letter
            'price' => 2000,
            'currency' => 'TWD'
        ], $rules);
        $this->assertFalse($validator->passes());
    }

    public function test_invalid_price_in_twd()
    {
        $request = new OrderStoreRequest();
        $rules = $request->rules();
        $validator = Validator::make([
            'name' => 'productname',
            'price' => 2500,
            'currency' => 'TWD'
        ], $rules);
        $this->assertFalse($validator->passes());
    }

    public function test_invalid_price_in_usd()
    {
        $data = [
            'name' => 'ProductName',
            'price' => 65, // 65 * 31 = 2015 > 2000
            'currency' => 'USD'
        ];
        $temp_rules = [
            'name' => [
                'required',
                'regex:/^[A-Z][a-zA-Z]*$/'
            ],
            'price' => [
                'required',
                'numeric',
                new PriceRule($data['currency'])
            ],
            'currency' => 'required|in:TWD,USD',
        ];
        // Test price validation for USD
        $validator = Validator::make($data, $temp_rules);
        $this->assertFalse($validator->passes());
    }

    public function test_valid_price_in_usd()
    {
        $data = [
            'name' => 'ProductName',
            'price' => 60, // 60 * 31 = 1860 <= 2000
            'currency' => 'USD'
        ];
        $temp_rules = [
            'name' => [
                'required',
                'regex:/^[A-Z][a-zA-Z]*$/'
            ],
            'price' => [
                'required',
                'numeric',
                new PriceRule($data['currency'])
            ],
            'currency' => 'required|in:TWD,USD',
        ];
        // Valid case for USD
        $validator = Validator::make($data, $temp_rules);
        $this->assertTrue($validator->passes());
    }

    public function test_invalid_currency()
    {
        $request = new OrderStoreRequest();
        $rules = $request->rules();
        $data = [
            'name' => 'ProductName',
            'price' => 2000,
            'currency' => 'EUR' // not TWD or USD
        ];
        // Test currency validation
        $validator = Validator::make($data, $rules);
        $this->assertFalse($validator->passes());
    }
}
