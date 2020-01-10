<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $fillable = [
        "id", "customer_id", "created_at", "updated_at", "table_number"
    ];

    public function customer() {
        return $this->belongsTo(User::class, "customer_id");
    }

    public function items() {
        return $this->belongsToMany(Item::class, "order_items", "order_id", "item_id")->withPivot('quantity');
    }

    public function getTotal() {
        // there's probably a nice one-liner to do this using the pivots
        $total = 0;
        foreach ($this->items as $item) {
            $total += ($item->pivot->quantity * $item->pence_cost);
        }
        return $total;
    }

}
