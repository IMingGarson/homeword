<?php
namespace Tests\Feature;

use Illuminate\Routing\Route;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;
use App\Http\Controllers\OrderController;

class OrderControllerTest extends TestCase
{
    public function test_valid_order_store_request()
    {
        $data = [
            'name' => 'Name',
            'price' => 1800,
            'currency' => 'TWD',
        ];
        $response = $this->json('POST', '/api/orders', $data);
        $response->assertStatus(201);
    }

    public function test_invalid_name_request()
    {
        $data = [
            'name' => 'name',
            'price' => 1800,
            'currency' => 'TWD',
        ];
        $response = $this->json('POST', '/api/orders', $data);
        $response->assertStatus(400);
    }

    public function test_invalid_price_in_twd_request()
    {
        $data = [
            'name' => 'name',
            'price' => 5000,
            'currency' => 'TWD',
        ];
        $response = $this->json('POST', '/api/orders', $data);
        $response->assertStatus(400);
    }

    public function test_invalid_price_in_usd_request()
    {
        $data = [
            'name' => 'name',
            'price' => 1000,
            'currency' => 'USD',
        ];
        $response = $this->json('POST', '/api/orders', $data);
        $response->assertStatus(400);
    }
}

?>