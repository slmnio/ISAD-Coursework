<?php

namespace App\Http\Controllers;

use App\Item;
use App\Order;
use DemeterChain\C;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
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

    public function emptyBasket($suppressMessage = false) {
        $cart = new Collection();
        session()->put('cart', $cart);
        if (!$suppressMessage) session()->flash("success-message", "Basket emptied.");
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
        session()->flash("success-message", "Removed from your basket.");
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



    public function order(Request $request) {
        $table = $request->input('table_number');
        $bitems = array_values($this->getBasketItems());

        $order = Auth::user()->orders()->create([
            "table_number" => $table
        ]);

        foreach ($bitems as $bitem) {
            $item = $bitem->item;
            $order->items()->attach($item, [
                "quantity" => $bitem->quantity
            ]);
        }


        $this->emptyBasket(true);

        session()->flash("success-message", "Order #". $order->id ." placed.");

        return Response::json(["redirect" => route('order.view', $order)], 201);

    }

    public function items() {
        $items = Item::with('category')
            ->where('enabled', 1)
            ->get();

        // Item has $hidden quantity, so you can't publicly see quantity

        return Response::json(["items" => $items], 200);
    }
}
