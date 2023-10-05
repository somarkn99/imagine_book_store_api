<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\CreateNewOrderRequest;
use App\Models\Book;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = request('per_page', 10); // Number of items per page (default is 10)

        $orders = Order::where('user_id', Auth::user()->id) // Assuming 'user_id' is the foreign key for the user
            ->with('books')
            ->paginate($perPage);

        $orders = Order::where('id', Auth::user()->id)->with('books')->get();

        return response()->json([
            'status' => 'success',
            'message' => trans('general.get'),
            'data' => $orders,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateNewOrderRequest $request)
    {
        $data = $request->validated();

        $cart = Cart::where('id', $data['cart_id'])->with('book')->first();

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
    }
}
