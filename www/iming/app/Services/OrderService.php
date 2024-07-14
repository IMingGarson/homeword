<?php
namespace App\Services;

use App\Enums\Currency;
use App\Constants\Price;

class OrderService
{
    public function store(array $data)
    {
        $response = ['code' => 201, 'message' => '', 'data' => $data];
        if (!isset($data['name'])) {
            $response['code'] = 400;
            $response['message'] = 'Name is required';
            return $response;
        }

        if (!isset($data['price'])) {
            $response['code'] = 400;
            $response['message'] = 'Price is required';
            return $response;
        }

        if (!isset($data['currency'])) {
            $response['code'] = 400;
            $response['message'] = 'Currency is required';
            return $response;
        }

        $name = explode(" ", $data['name']);
        $price = $data['price'];
        $currency = $data['currency'];
        foreach ($name as $w) {
            if (!if_first_letter_is_uppercase($w)) {
                $response['code'] = 400;
                $response['message'] = 'Name is not capitalized';
                return $response;
            }
        }

        if (!is_numeric($price)) {
            $response['code'] = 400;
            $response['message'] = 'Price is wrong';
            return $response;
        }

        if ($currency != Currency::USD->value && $currency != Currency::TWD->value) {
            $response['code'] = 400;
            $response['message'] = 'Currency format is wrong';
            return $response;
        }

        if ($currency == Currency::USD->value && intval($price) * Price::USD_EXCHANGE_RATE > Price::NTD_PRICE_LIMIT) {
            $response['code'] = 400;
            $response['message'] = 'Price is over 2000';
            return $response;
        }
        

        if ($currency == Currency::USD->value) {
            $data['price'] = $data['price'] * Price::USD_EXCHANGE_RATE;
            $data['currency'] = Currency::TWD->value;
        }
        
        $response = ['code' => 201, 'message' => 'Success', 'data' => $data];
        return $response;
    }
}

?>