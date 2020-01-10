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
        return Response::json(["redirect" => route('order.list')], 200);
    }
}
