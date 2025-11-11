<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(
        private readonly CartService $cartService,
    ) {
    }

    public function add(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
            ]);

            $product = Product::findOrFail($validated['product_id']);
            $cartItem = $this->cartService->addProduct(auth()->user(), $product, $validated['quantity']);

            $cartCount = $this->cartService->getCartCount(auth()->user());

            return response()->json([
                'success' => true,
                'message' => 'Товар добавлен в корзину',
                'cart_count' => $cartCount,
                'product_id' => $product->id,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка валидации',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Товар не найден',
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Cart add error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при добавлении товара: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function remove(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
            ]);

            $product = Product::findOrFail($validated['product_id']);
            $this->cartService->removeProduct(auth()->user(), $product);

            $cartCount = $this->cartService->getCartCount(auth()->user());
            $cart = $this->cartService->getOrCreateCartForUser(auth()->user());
            $cart->load('items.product');
            
            $total = $cart->items->sum(fn($item) => $item->product->price * $item->quantity);

            return response()->json([
                'success' => true,
                'message' => 'Товар удален из корзины',
                'cart_count' => $cartCount,
                'product_id' => $product->id,
                'total' => $total,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка валидации',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Товар не найден',
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Cart remove error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при удалении товара: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getCount(Request $request): JsonResponse
    {
        $cartCount = $this->cartService->getCartCount(auth()->user());

        return response()->json([
            'cart_count' => $cartCount,
        ]);
    }

    public function updateQuantity(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
            ]);

            $product = Product::findOrFail($validated['product_id']);
            $this->cartService->updateQuantity(auth()->user(), $product, $validated['quantity']);

            $cartCount = $this->cartService->getCartCount(auth()->user());
            $cart = $this->cartService->getOrCreateCartForUser(auth()->user());
            $cart->load('items.product');
            
            $total = $cart->items->sum(fn($item) => $item->product->price * $item->quantity);

            return response()->json([
                'success' => true,
                'message' => 'Количество обновлено',
                'cart_count' => $cartCount,
                'total' => $total,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка валидации',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Cart update quantity error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при обновлении количества: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getCartState(Request $request): JsonResponse
    {
        try {
            $cartItems = $this->cartService->getCartItems(auth()->user());
            $cartCount = $this->cartService->getCartCount(auth()->user());

            return response()->json([
                'success' => true,
                'items' => $cartItems,
                'cart_count' => $cartCount,
            ]);
        } catch (\Exception $e) {
            \Log::error('Cart get state error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при получении состояния корзины',
            ], 500);
        }
    }
}
