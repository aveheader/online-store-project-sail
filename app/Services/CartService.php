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
}
