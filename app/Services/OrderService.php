<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function createOrder($cartId)
    {
        try {
            $cart = Cart::where('id', $cartId)->with('book')->first();

            if (! $cart) {
                throw new ModelNotFoundException('Cart not found');
            }

            $totalPrice = $cart->qty * $cart->book->price;

            DB::beginTransaction();

            $order = Order::create([
                'user_id' => Auth::user()->id,
                'book_id' => $cart->book_id,
                'qty' => $cart->qty,
                'total_price' => $totalPrice,
            ]);

            $cart->delete();

            Book::where('id', $cart->book->id)->update([
                'stock' => $cart->book->stock - $cart->qty,
            ]);

            DB::commit();

            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
