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
        return Response::json(["redirect" => route('admin.item.list')], 200);
    }
    public function creator() {
        return view('admin.items.creator');
    }
    public function create(Request $request) {
/*            actuator.bindElement("name", "#inputName");
            actuator.bindElement("description", "#inputDescription");
            actuator.bindElement("cost_pence", "#inputPence");
            actuator.bindElement("category_id", "#inputCategory");*/

        $item = Item::create([
            "name" => $request->input('name'),
            "description" => $request->input('description'),
            "cost_pence" => $request->input('cost_pence'),
            "category_id" => $request->input('category_id'),
            "enabled" => 1
        ]);
        session()->flash("success-message", $item->name . " created.");
        return Response::json(["redirect" => route('admin.item.view', $item)], 200);
    }
    public function toggle(Item $item) {
        $item->toggle();
        $item->save();
        session()->flash("success-message", $item->name . " is now " . ($item->enabled ? "enabled." : "disabled."));
        return Response::json(["reload" => true], 200);
    }
}
