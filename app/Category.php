<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        "name"
    ];

    public function items() {
        return $this->hasMany(Item::class);
    }

    public function getSlug() {
        // regex to make things "url/file-safe" (https://stackoverflow.com/a/34244525)
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->name)));
    }
    public function getImage() {
        return $this->getSlug() . ".png";
    }

}
