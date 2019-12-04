<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Drink extends Model
{
    //

    protected $hidden = [];


    public function getFormattedPrice() {
        return env('SITE_CURRENCY') . number_format($this->price / 100, 2);
    }


}
