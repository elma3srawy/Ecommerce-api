<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Events\ItemRemoved;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Traits\ProductQueries;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\CartResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\Cart\CartStoreRequest;
use App\Http\Requests\Cart\RemoveFromCartRequest;

class CartController extends Controller
{

    private string $session_id;
    private ?string $auth_id;

    public function __construct()
    {
        $this->session_id = Session::id();
        $this->auth_id = Auth::guard('user')->id();
    }
    public function addToCart(CartStoreRequest $request)
    {
        $cart = Cart::findCart($this->auth_id , $this->session_id);

        if (!$cart) {
            $cart = Cart::create([
                'user_id' => $this->auth_id,
                'session_id' => $this->session_id,
            ]);
        } else {
            $cart->update([
                'user_id' => $this->auth_id,
                'session_id' => $this->session_id,
            ]);
        }

        $this->storeCartItems($request , $cart);

        $cart =  CartResource::collection($cart->getCart());

        return response()->json(['message' => 'Item added to cart successfully' , 'date' => $cart], 200);
    }

    private function storeCartItems(Request $request ,Cart $cart)
    {
        collect($request->cart)->each(function ($reqCart) use ($cart) {
            $cart->items()->updateOrCreate(
                ['product_id' => $reqCart['product_id']],
                ['quantity' => $reqCart['quantity']]
            );
        });
    }

    public function removeFromCart(RemoveFromCartRequest $request)
    {
        $cart = Cart::findCart($this->auth_id, $this->session_id);

        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }

        $deletedItems = CartItem::where('cart_id', $cart->id)
            ->whereIn('product_id', $request->product_ids)
            ->delete();

        if ($deletedItems > 0) {
            return response()->json(['message' => 'Items successfully removed from the cart'], 200);
        }

        event(new ItemRemoved($cart->id));

        return response()->json(['message' => 'No matching items found in the cart'], 404);
    }

    public function clearCart(Request $request)
    {
        $cart = Cart::findCart($this->auth_id , $this->session_id);
        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }
        $cart->delete();

        return response()->json(['message' => 'Cart successfully cleared'], 200);

    }
    public function getCart(Request $request)
    {
        $cart = Cart::findCart($this->auth_id, $this->session_id);

        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }

        $cart =  CartResource::collection($cart->getCart());

        return response()->json(['cart' => $cart], 200);
    }
}
