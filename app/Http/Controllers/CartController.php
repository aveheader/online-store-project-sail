<?php

namespace App\Http\Controllers;

use App\Models\Product;
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
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        $this->cartService->addProduct(auth()->user(), $product, $request->quantity);

        return redirect()->back()->with('success', 'Товар добавлен в корзину');
    }

    public function delete(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $product = Product::findOrFail($request->product_id);

        $this->cartService->removeProduct(auth()->user(), $product);

        return redirect()->back()->with('success', 'Товар удален из корзины');
    }
}
