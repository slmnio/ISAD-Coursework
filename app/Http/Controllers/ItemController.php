<?php

namespace App\Http\Controllers;

use App\Item;
use DemeterChain\C;
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
        session()->flash("success-message", "Basket emptied.");
        return Response::json(["reload" => true], 200);
    }

    public function removeCartItem(Request $request) {
        $reqitem = Item::find($request->input('item_id'));
        if (!$reqitem) abort(404);

        $cart = session()->get('cart');

        if (!$cart) abort(400);


        $i = 0;

        $newCart = new Collection();

        $takenOne = false;
        foreach($cart as $item) {

            if ($item->id === $reqitem->id && !$takenOne) {
                $takenOne = true;
                //dd($cart, $i, $item, $reqitem);
                //$cart->forget($i);
            } else {
                $newCart->add($item);
            }
            $i++;
        }
        session()->put('cart', $newCart);
        return Response::json(["reload" => true], 200);
    }

    public function getBasketItems() {
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
        return $basketItems;
    }

    public function getTotal() {
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
        return $total;
    }

    public function viewBasket() {
        return view('basket')->with([
            'basketItems' => array_values($this->getBasketItems()),
            'total' => $this->getTotal()
        ]);
    }
}
