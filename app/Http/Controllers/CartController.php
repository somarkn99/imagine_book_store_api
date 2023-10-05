<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cart\AddToCartRequest;
use App\Http\Requests\Cart\UpdateCartRequest;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cart = Cart::where('user_id', Auth::user()->id)->with('book')->get();

        return response()->json([
            'status' => 'success',
            'message' => trans('general.get'),
            'data' => $cart,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddToCartRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::user()->id;
        Cart::create($data);

        return response()->json([
            'status' => 'success',
            'message' => trans('general.store'),
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCartRequest $request, Cart $cart)
    {
        // Ensure the user is authorized to perform the update
        if (Gate::denies('update', $cart)) {
            return response()->json([
                'status' => 'error',
                'message' => trans('general.notAllowed'),
            ], 403); // HTTP status code for forbidden access
        }

        $data = $request->validated();

        // Only update the fields that have changed in the request
        $fieldsToUpdate = array_filter($data, function ($key) use ($request, $cart, $data) {
            return $request->has($key) && $cart->{$key} !== $data[$key];
        }, ARRAY_FILTER_USE_KEY);

        // Update the book with the selected fields
        $cart->update($fieldsToUpdate);

        return response()->json([
            'status' => 'success',
            'message' => trans('general.update'),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        // Check if the user is authorized to update the address
        if (Gate::denies('delete', $cart)) {
            // Handle unauthorized access with a JSON response
            return response()->json([
                'status' => 'error',
                'message' => trans('general.notAllowed'),
            ], 403); // HTTP status code for forbidden access
        }

        $cart->delete();

        return response()->json([], 202);
    }
}
