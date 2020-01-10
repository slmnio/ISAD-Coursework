<?php

namespace App\Http\Controllers;

use App\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class AdminItemController extends Controller
{
    public function list(){
        $items = Item::paginate(10);
        return view('admin.items.list')->with('items', $items);
    }
    public function view(Item $item) {
        return view('admin.items.view')->with('item', $item);
    }
    public function delete(Item $item) {
        session()->flash("success-message", $item->name . " deleted.");
        $item->delete();
        return Response::json(["redirect" => route('admin.items.list')], 200); route('admin.items.list');
    }
    public function creator() {
        return view('admin.items.creator');
    }
    public function create(Request $request) {

    }
}
