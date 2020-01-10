<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        "name", "description", "category_id", "cost_pence", "quantity", "enabled"
    ];

    public static function formatPrice($pence) {
        return (env('SITE_CURRENCY', "£")) . number_format($pence / 100, 2);
    }


    public function getFormattedPrice() {
        return (env('SITE_CURRENCY', "£")) . number_format($this->cost_pence / 100, 2);
    }


    public function orders() {
        return $this->belongsToMany(Order::class, "order_items", "item_id", "order_id")->withPivot('quantity');
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public $timestamps = false;

    public function getImage() {
        // random 100x100 food image
        return "https://source.unsplash.com/random/100x100?". $this->category->slug  . $this->id; // bypass cache
    }
}
