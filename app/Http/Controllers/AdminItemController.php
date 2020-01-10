<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminItemController extends Controller
{
    public function list(){
        $items = Item::all()->paginate(10);
        return view('admin.items.list')->with("items", $items);
    }
}
