<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class OrderController extends Controller
{
    public function view(Order $order) {
        if ($order->customer->id !== Auth::user()->id) abort(403);
        return view('orders.view')->with("order", $order);
    }
    public function list(){
        $orders = Auth::user()->orders()->orderBy('created_at', 'desc')->paginate(4);
        return view('orders.list')->with("orders", $orders);
    }
    public function delete(Order $order) {
        if ($order->customer->id !== Auth::user()->id) abort(403);
        $order->delete();
        session()->flash("success-message", "Order deleted.");
        return Response::json(["redirect" => route('order.list')], 200);
    }
    public function alterQuantity(Order $order, Request $request) {
        $quantityChange = $request->input('change');
        if (!$quantityChange) abort(400, "No change submitted");
        $item = $order->items()->find($request->input('item_id'));
        if (!$item) abort(400, "Couldn't find that item in this order.");

        $prevQuantity = $order->items->find($item)->pivot->quantity;

        if ($prevQuantity === 1 && $quantityChange === -1) {
            $order->items()->detach($item);
            session()->flash("success-message", "The item has been removed.");
            return Response::json(["reload" => true], 200);
        }

        $order->items()->updateExistingPivot($item, [
            "quantity" => $prevQuantity += $quantityChange
        ]);

        session()->flash("success-message", "The item quantity has been adjusted.");
        return Response::json(["reload" => true], 200);
    }
}
