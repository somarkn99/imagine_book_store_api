<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\CreateNewOrderRequest;
use App\Models\Book;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderController extends Controller
{
    public function __construct()
    {
        // Assign only to specific methods in this Controller
        $this->middleware(['auth:api', 'CheckQty'])->only('store');
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

            $cart = Cart::where('id', $data['cart_id'])->with('book')->first();

            if (!$cart) {
                throw new ModelNotFoundException('Cart not found');
            }

            $total_price = $cart->qty * $cart->book->price;

            DB::beginTransaction();

            Order::create([
                'user_id' => Auth::user()->id,
                'book_id' => $cart->book_id,
                'qty' => $cart->qty,
                'total_price' => $total_price,
            ]);

            $cart->delete();

            Book::where('id', $cart->book->id)->update([
                'stock' => $cart->book->stock - $cart->qty,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => trans('general.store'),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
