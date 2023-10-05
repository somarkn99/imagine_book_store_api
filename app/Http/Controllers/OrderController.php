<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\CreateNewOrderRequest;
use App\Models\Book;
use App\Models\Cart;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderController extends Controller
{
    private $orderService;

    public function __construct(OrderService $orderService)
    {
        // Assign only to specific methods in this Controller
        $this->middleware(['auth:api', 'CheckQty'])->only('store');

        $this->orderService = $orderService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $perPage = request('per_page', 10); // Number of items per page (default is 10)

            $orders = Order::where('user_id', Auth::user()->id)
                ->with('books')
                ->paginate($perPage);

            return response()->json([
                'status' => 'success',
                'message' => trans('general.get'),
                'data' => $orders,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateNewOrderRequest $request)
    {
        try {
            $data = $request->validated();
            $order = $this->orderService->createOrder($data['cart_id']);

            return response()->json([
                'status' => 'success',
                'message' => trans('general.store'),
                'data' => $order,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
