<?php

namespace Tests\Unit;

use App\Http\Requests\OrderStoreRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;
use App\Rules\PriceRule;

class OrderRequestTest extends TestCase
{
    private function get_rules()
    {
        return (new OrderStoreRequest())->rules();
    }

    public function test_valide_order_store_request()
    {
        $rules = $this->get_rules();

        $validator = Validator::make([
            'name' => 'ProductName',
            'price' => 1599,
            'currency' => 'TWD'
        ], $rules);

        $this->assertTrue($validator->passes());
    }

    public function test_non_captialized_first_letter_in_name()
    {
        $rules = $this->get_rules();
        $validator = Validator::make([
            'name' => 'productname', // non-capitalized first letter
            'price' => 2000,
            'currency' => 'TWD'
        ], $rules);
        $this->assertFalse($validator->passes());
    }

    public function test_invalid_price_in_twd()
    {
        $rules = $this->get_rules();
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
        $rules = $this->get_rules();
        $rules['price'] = [
            'required',
            'numeric',
            new PriceRule($data['currency'])
        ];
        // Test price validation for USD
        $validator = Validator::make($data, $rules);
        $this->assertFalse($validator->passes());
    }

    public function test_valid_price_in_usd()
    {
        $data = [
            'name' => 'ProductName',
            'price' => 60, // 60 * 31 = 1860 <= 2000
            'currency' => 'USD'
        ];
        $rules = $this->get_rules();
        $rules['price'] = [
            'required',
            'numeric',
            new PriceRule($data['currency'])
        ];
        // Valid case for USD
        $validator = Validator::make($data, $rules);
        $this->assertTrue($validator->passes());
    }

    public function test_invalid_currency()
    {
        $rules = $this->get_rules();
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
