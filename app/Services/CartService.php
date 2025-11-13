<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Validation\ValidationException;

class CartService
{
    public function getOrCreateCartForUser($user)
    {
        return Cart::firstOrCreate(['user_id' => $user->id]);
    }

    public function addProduct($user, Product $product, int $quantity = 1)
    {
        $cart = $this->getOrCreateCartForUser($user);

        if ($quantity < 1) {
            throw ValidationException::withMessages(['quantity' => 'Quantity must be at least 1']);
        }

        $item = $cart->items()->firstOrNew(['product_id' => $product->id]);
        $item->quantity = ($item->exists ? $item->quantity : 0) + $quantity;
        $item->save();

        return $item;
    }

    public function removeProduct($user, Product $product)
    {
        $cart = $this->getOrCreateCartForUser($user);

        return $cart->items()->where('product_id', $product->id)->delete();
    }

    public function getCartCount($user): int
    {
        $cart = $this->getOrCreateCartForUser($user);
        return $cart->items()->sum('quantity');
    }

    public function isProductInCart($user, Product $product): bool
    {
        $cart = $this->getOrCreateCartForUser($user);
        return $cart->items()->where('product_id', $product->id)->exists();
    }

    public function updateQuantity($user, Product $product, int $quantity): void
    {
        $cart = $this->getOrCreateCartForUser($user);

        if ($quantity < 1) {
            throw ValidationException::withMessages(['quantity' => 'Quantity must be at least 1']);
        }

        $item = $cart->items()->where('product_id', $product->id)->first();

        if ($item) {
            $item->quantity = $quantity;
            $item->save();
        } else {
            throw ValidationException::withMessages(['product' => 'Product not found in cart']);
        }
    }

    public function getProductQuantity($user, Product $product): int
    {
        $cart = $this->getOrCreateCartForUser($user);
        $item = $cart->items()->where('product_id', $product->id)->first();
        return $item ? $item->quantity : 0;
    }

    public function getCartItems($user): array
    {
        $cart = $this->getOrCreateCartForUser($user);
        return $cart->items()->with('product')->get()->map(function ($item) {
            return [
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
            ];
        })->toArray();
    }
}
