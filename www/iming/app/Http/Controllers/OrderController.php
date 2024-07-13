<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\OrderStoreRequest;
use App\Services\OrderService;

class OrderController extends Controller
{
    private $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        return response()->json(['message' => 'Pong.'], 200);
    }

    public function store(OrderStoreRequest $request)
    {
        $validated = $request->validate();

        $res = $this->orderService->store($validated);
        if ($res) {
            return response()->json(['message' => 'Order created successfully.'], 201);
        }
        return response()->json(['message' => 'Something went wrong.'], 500);
    }
    
}
