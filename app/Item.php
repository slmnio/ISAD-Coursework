<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        "name", "description", "category_id", "cost_pence", "quantity", "enabled"
    ];

    public function getFormattedPrice() {
        return (env('SITE_CURRENCY', "£")) . number_format($this->cost_pence / 100, 2);
    }

    public function orders() {
        return $this->belongsToMany(Order::class, "order_items", "item_id", "order_id");
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public $timestamps = false;

    public function getImage() {
        // random 100x100 food image
        return "https://lorempixel.com/100/100/food/";
    }
}
