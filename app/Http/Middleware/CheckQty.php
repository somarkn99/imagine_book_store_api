<?php

namespace App\Http\Middleware;

use App\Models\Cart;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckQty
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $cart = Cart::where('id', $request->cart_id)->with('book')->first();
        if ($cart->book->stock == 0) {
            return response()->json([
                'status' => 'success',
                'message' => trans('general.EmptyStock'),
            ], 201);
        }
        return $next($request);
    }
}
