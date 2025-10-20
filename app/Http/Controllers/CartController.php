<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(
        private readonly CartService $cartService,
    ) {
    }

    public function index()
    {
        $cart = $this->cartService->getOrCreateCartForUser(auth()->user());
        $cart->load('items.product');
        return view('cart.index', compact('cart'));
    }

    public function add(Request $request)
    {

    }

    public function delete(Request $request)
    {

    }
}
