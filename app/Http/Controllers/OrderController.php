<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function view(Order $order) {
        if ($order->customer->id !== Auth::user()->id) abort(403);
        return view('orders.view', $order);
    }
    public function list(){
        $orders = Auth::user()->orders;
        return view('orders.list', $orders);
    }
}
