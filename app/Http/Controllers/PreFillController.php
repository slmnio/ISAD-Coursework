<?php

namespace App\Http\Controllers;

use App\Category;
use App\Item;
use Illuminate\Http\Request;

class PreFillController extends Controller
{
    public function items()
    {
        // Delete all
        foreach(Item::all() as $item) {
            $item->delete();
        }

        $category = Category::where('name', 'Drinks')->first()->id;
        Item::create([
            "name" => "Broweiser",
            "description" => "Delicious beer: 2.5%",
            "cost_pence" => 365,
            "quantity" => 32,
            "category_id" => $category
        ]);
        Item::create([
            "name" => "Coke",
            "description" => "Tasty & refreshing",
            "cost_pence" => 160,
            "quantity" => 50,
            "category_id" => $category
        ]);
        Item::create([
            "name" => "Jack Daniels",
            "description" => "Pour one out",
            "cost_pence" => 320,
            "quantity" => 8,
            "category_id" => $category
        ]);
        Item::create([
            "name" => "Vodka Shot",
            "description" => "65% ABV. May include Vodka",
            "cost_pence" => 2500,
            "quantity" => 20,
            "category_id" => $category
        ]);
        Item::create([
            "name" => "Mountain Dew",
            "description" => "To improve your typing",
            "cost_pence" => 195,
            "quantity" => 12,
            "category_id" => $category
        ]);


        $category = Category::where('name', 'Snacks')->first()->id;
        Item::create([
            "name" => "Crisps",
            "description" => "crunchy...",
            "cost_pence" => 75,
            "quantity" => 12,
            "category_id" => $category
        ]);
        Item::create([
            "name" => "Peanuts",
            "description" => "very crunchy...",
            "cost_pence" => 125,
            "quantity" => 8,
            "category_id" => $category
        ]);

        $category = Category::where('name', 'Alcohol-free')->first()->id;
        Item::create([
            "name" => "Orange juice",
            "description" => "(aka OJ)",
            "cost_pence" => 275,
            "quantity" => 8,
            "category_id" => $category
        ]);

        $category = Category::where('name', 'Desserts')->first()->id;
        Item::create([
            "name" => "Waffle",
            "description" => "with vanilla ice-cream",
            "cost_pence" => 675,
            "quantity" => 14,
            "category_id" => $category
        ]);

        return redirect()->route('home');
    }
}
