<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\CreateNewOrderRequest;
use App\Models\Book;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
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
            'stock' => $cart->book->stock - $cart->qty
        ]);

        DB::commit();

        return response()->json([
            'status' => 'success',
            'message' => trans('general.store'),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    // public function show(Order $order)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, Order $order)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(Order $order)
    // {
    //     //
    // }
}
