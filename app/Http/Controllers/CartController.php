<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cart\AddToCartRequest;
use App\Http\Requests\Cart\UpdateCartRequest;
use App\Models\Cart;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    private $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $cart = $this->cartService->getUserCart();

            return response()->json([
                'status' => 'success',
                'message' => trans('general.get'),
                'data' => $cart,
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
    public function store(AddToCartRequest $request)
    {
        try {
            $data = $request->validated();
            $this->cartService->addToCart($data);

            return response()->json([
                'status' => 'success',
                'message' => trans('general.store'),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCartRequest $request, Cart $cart)
    {
        try {
            // Ensure the user is authorized to perform the update
            if (Gate::denies('update', $cart)) {
                return response()->json([
                    'status' => 'error',
                    'message' => trans('general.notAllowed'),
                ], 403); // HTTP status code for forbidden access
            }

            $data = $request->validated();
            $this->cartService->updateCart($cart, $data, $request);

            // Only update the fields that have changed in the request
            return response()->json([
                'status' => 'success',
                'message' => trans('general.update'),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        try {
            // Check if the user is authorized to delete the cart item
            if (Gate::denies('delete', $cart)) {
                // Handle unauthorized access with a JSON response
                return response()->json([
                    'status' => 'error',
                    'message' => trans('general.notAllowed'),
                ], 403); // HTTP status code for forbidden access
            }

            $cart->delete();

            return response()->json([], 202);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
