<?php

namespace App\Http\Controllers;

use App\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;

class ItemController extends Controller
{
    public function addToCart(Request $request) {
        $item = Item::find($request->input('item_id'));
        if (!$item) abort(404);

        $cart = session()->get('cart');

        if (!$cart) {
            $cart = new Collection();
        }

        $cart->add($item);

        session()->put('cart', $cart);

        return Response::json(session()->get('cart'), 200);
    }

    public function emptyCart() {
        $cart = new Collection();
        session()->put('cart', $cart);
        return Response::json(session()->get('cart'), 200);
    }

    public function viewBasket() {
        $basketItems = [];

        $total = 0;

        foreach(session()->get('cart') as $item) {
             if (!isset($basketItems[strval($item->id)])) {
                 $basketItems[strval($item->id)] = (object) [
                     "item" => $item,
                     "quantity" => 1
                 ];
                 $total += $item->cost_pence;
             } else {
                 $basketItems[strval($item->id)]->quantity++;
                 $total += $item->cost_pence;
             }
        }

        return view('basket')->with([
            'basketItems' => array_values($basketItems),
            'total' => $total
        ]);
    }
}
