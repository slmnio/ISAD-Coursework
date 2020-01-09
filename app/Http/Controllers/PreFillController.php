<?php

namespace App\Http\Controllers;

use App\Category;
use App\Item;
use Illuminate\Http\Request;

class PreFillController extends Controller
{
    public function items()
    {
        Item::create([
            "name" => "Broweiser",
            "description" => "Delicious beer: 2.5%",
            "cost_pence" => 365,
            "quantity" => 32,
            "category_id" => Category::where('name', 'Drinks')->first()->id
        ]);
        Item::create([
            "name" => "Broweiser",
            "description" => "Delicious beer: 2.5%",
            "cost_pence" => 365,
            "quantity" => 32,
            "category_id" => Category::where('name', 'Drinks')->first()->id
        ]);

        return redirect()->route('home');
    }
}
