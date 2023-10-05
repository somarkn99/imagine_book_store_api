<?php

namespace App\Services;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartService
{
    public function getUserCart()
    {
        return Cart::where('user_id', Auth::user()->id)->with('book')->get();
    }

    public function addToCart(array $data)
    {
        $data['user_id'] = Auth::user()->id;
        Cart::create($data);
    }

    public function updateCart(Cart $cart, array $data, $request)
    {
        $fieldsToUpdate = array_filter($data, function ($key) use ($request, $cart, $data) {
            return $request->has($key) && $cart->{$key} !== $data[$key];
        }, ARRAY_FILTER_USE_KEY);

        // Update the cart with the selected fields
        $cart->update($fieldsToUpdate);
    }
}
