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

    public function store(OrderStoreRequest $request)
    {
        $validated = $request->validated();

        $res = $this->orderService->store($validated);
        if ($res['code'] == 201) {
            return response()->json($res, 201);
        }
        return response()->json($res, 400);
    }
}
